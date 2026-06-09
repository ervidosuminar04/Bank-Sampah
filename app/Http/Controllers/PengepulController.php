<?php

namespace App\Http\Controllers;

use App\Models\Pengepul;
use App\Models\TransaksiPengepul;
use App\Models\SetoranPengepul;
use App\Models\Nasabah;
use App\Models\Sampah;
use App\Models\Tabungan;
use App\Models\Gamifikasi;
use Illuminate\Http\Request;

class PengepulController extends Controller
{
    /**
     * Dashboard utama pengepul – menampilkan statistik & form timbangan
     */
    public function dashboard(Request $request)
    {
        $pengepulId = session('user_id');
        $pengepul   = Pengepul::find($pengepulId);
        if (!$pengepul) {
            session()->flush();
            return redirect()->route('login')->withErrors(['username' => 'Sesi Anda telah kedaluwarsa atau tidak valid. Silakan masuk kembali.']);
        }

        // Statistik umum
        $totalTransaksi   = $pengepul->transaksi()->count();
        $totalBeratKg     = $pengepul->transaksi()->sum('berat_kg');
        $totalNilaiRupiah = $pengepul->transaksi()->sum('nilai_idr');
        $totalKomisi      = $pengepul->transaksi()->sum('transaksi_pengepul_komisi_pengepul');

        // Transaksi bulan ini (untuk tab Dashboard)
        $transaksibulanIni = $pengepul->transaksi()
            ->with(['nasabah', 'sampah'])
            ->whereMonth('transaksi_pengepul_tanggal', now()->month)
            ->whereYear('transaksi_pengepul_tanggal', now()->year)
            ->orderByDesc('transaksi_pengepul_tanggal')
            ->get();

        // Daftar nasabah aktif untuk dropdown timbangan
        $nasabahs = Nasabah::where('nasabah_status', 'aktif')
            ->orderBy('nasabah_nama')
            ->get();

        // Daftar jenis sampah untuk dropdown timbangan
        $sampahs = Sampah::orderBy('sampah_nama')->get();

        // Transaksi yang belum disetor (untuk tab Setoran)
        $transaksiBelumDisetor = $pengepul->transaksiBelumDisetor()
            ->with(['nasabah', 'sampah'])
            ->orderByDesc('transaksi_pengepul_tanggal')
            ->get();

        $totalBelumDisetor         = $transaksiBelumDisetor->sum('nilai_idr');
        $totalSelisihBelumDisetor  = $transaksiBelumDisetor->sum('selisih_total');
        $totalKomisiBelum          = $transaksiBelumDisetor->sum('transaksi_pengepul_komisi_pengepul');
        $totalAdminBelum           = $transaksiBelumDisetor->sum('bagian_admin');
        $totalHarusDisetor         = $totalBelumDisetor + $totalAdminBelum;

        // Riwayat setoran
        $riwayatSetoran = $pengepul->setoran()
            ->orderByDesc('id_setoran_pengepul')
            ->get();

        // Data laporan – diload saat filter dikirim via query param
        $laporanBulan     = $request->query('bulan', now()->format('m'));
        $laporanTahun     = $request->query('tahun', now()->format('Y'));
        $laporanTransaksi = $pengepul->transaksi()
            ->with(['nasabah', 'sampah'])
            ->whereMonth('transaksi_pengepul_tanggal', $laporanBulan)
            ->whereYear('transaksi_pengepul_tanggal', $laporanTahun)
            ->orderBy('transaksi_pengepul_tanggal')
            ->get();
        $laporanTotalBerat  = $laporanTransaksi->sum('berat_kg');
        $laporanTotalNilai  = $laporanTransaksi->sum('nilai_idr');
        $laporanTotalKomisi = $laporanTransaksi->sum('transaksi_pengepul_komisi_pengepul');

        return view('pengepul.dashboard', compact(
            'pengepul',
            'totalTransaksi',
            'totalBeratKg',
            'totalNilaiRupiah',
            'totalKomisi',
            'transaksibulanIni',
            'nasabahs',
            'sampahs',
            'transaksiBelumDisetor',
            'totalBelumDisetor',
            'totalSelisihBelumDisetor',
            'totalKomisiBelum',
            'totalAdminBelum',
            'totalHarusDisetor',
            'riwayatSetoran',
            'laporanBulan',
            'laporanTahun',
            'laporanTransaksi',
            'laporanTotalBerat',
            'laporanTotalNilai',
            'laporanTotalKomisi'
        ));
    }

    /**
     * Proses timbangan & setor sampah nasabah
     */
    public function storeSetor(Request $request)
    {
        $data = $request->validate([
            'nasabah_id'       => 'required|integer|exists:nasabah,id_nasabah',
            'id_sampah'        => 'required|integer|exists:sampah,id_sampah',
            'berat_kg'         => 'required|numeric|min:0.01',
            'keterangan'       => 'nullable|string|max:255',
            'foto_dokumentasi' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $pengepulId = session('user_id');
        $pengepul   = Pengepul::findOrFail($pengepulId);
        $nasabah    = Nasabah::find($data['nasabah_id']);
        $sampah     = Sampah::find($data['id_sampah']);

        // ── Kalkulasi finansial ──────────────────────────────────────────
        $hargaBeliKg  = $sampah->sampah_harga_kg;
        $hargaPasarKg = $sampah->sampah_harga_pasar ?? $hargaBeliKg;
        $beratKg      = $data['berat_kg'];

        $nilaiIdr     = round($hargaBeliKg  * $beratKg, 2);
        $nilaiPasar   = round($hargaPasarKg * $beratKg, 2);
        $selisihTotal = round($nilaiPasar - $nilaiIdr, 2);

        $komisiPersen   = $pengepul->pengepul_komisi_persen ?? 50;
        $komisiPengepul = round($selisihTotal * $komisiPersen / 100, 2);
        $bagianAdmin    = round($selisihTotal - $komisiPengepul, 2);

        // ── Simpan transaksi pengepul ────────────────────────────────────
        TransaksiPengepul::create([
            'pengepul_id'                         => $pengepulId,
            'nasabah_id'                          => $data['nasabah_id'],
            'id_sampah'                           => $data['id_sampah'],
            'berat_kg'                            => $beratKg,
            'harga_beli_kg'                       => $hargaBeliKg,
            'harga_pasar_kg'                      => $hargaPasarKg,
            'nilai_idr'                           => $nilaiIdr,
            'selisih_total'                       => $selisihTotal,
            'transaksi_pengepul_komisi_pengepul'  => $komisiPengepul,
            'bagian_admin'                        => $bagianAdmin,
            'sudah_disetor'                       => false,
            'transaksi_pengepul_tanggal'          => now()->toDateString(),
            'transaksi_pengepul_keterangan'       => $data['keterangan'] ?? 'Setor ' . $sampah->sampah_nama . ' ' . $beratKg . ' kg via pengepul',
            'transaksi_pengepul_gambar'           => $request->hasFile('foto_dokumentasi') ? $request->file('foto_dokumentasi')->store('dokumentasi_timbangan', 'public') : null,
        ]);

        // ── Update tabungan nasabah ──────────────────────────────────────
        $tabungan = Tabungan::where('id_nasabah', $data['nasabah_id'])->first();
        if ($tabungan) {
            $tabungan->tabungan_total_setor += $nilaiIdr;
            $tabungan->tabungan_saldo_akhir += $nilaiIdr;
            $tabungan->tabungan_tgl_update   = now();
            $tabungan->save();
        }

        // ── Update saldo nasabah ─────────────────────────────────────────
        $nasabah->nasabah_saldo += $nilaiIdr;
        $nasabah->save();

        // ── Update gamifikasi nasabah (1 kg = 10 poin) ───────────────────
        $poinBaru   = round($beratKg * 10);
        $gamifikasi = Gamifikasi::where('id_nasabah', $data['nasabah_id'])->first();
        if ($gamifikasi) {
            $gamifikasi->poin_diperoleh += $poinBaru;
            $gamifikasi->total_poin     += $poinBaru;
            $totalPoin = $gamifikasi->total_poin;
            if ($totalPoin >= 500) {
                $gamifikasi->level_nasabah = 'Bintang';
                $gamifikasi->badge         = 'Penyetor Bintang';
            } elseif ($totalPoin >= 100) {
                $gamifikasi->level_nasabah = 'Aktif';
                $gamifikasi->badge         = 'Penyetor Konsisten';
            } else {
                $gamifikasi->level_nasabah = 'Pemula';
                $gamifikasi->badge         = 'Eco Starter';
            }
            $gamifikasi->tanggal_update = now();
            $gamifikasi->save();
        }

        return redirect()->route('pengepul.dashboard')
            ->with('success', 'Setoran berhasil! Saldo nasabah +Rp ' . number_format($nilaiIdr, 0, ',', '.') . '. Komisi Anda: Rp ' . number_format($komisiPengepul, 0, ',', '.'));
    }

    /**
     * Pengepul membuat setoran ke admin (batch)
     */
    public function storeSetoran(Request $request)
    {
        $data = $request->validate([
            'transaksi_ids'    => 'required|array|min:1',
            'transaksi_ids.*'  => 'integer|exists:transaksi_pengepul,id_transaksi_pengepul',
            'foto_dokumentasi' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $pengepulId = session('user_id');
        $pengepul   = Pengepul::findOrFail($pengepulId);

        $transaksi = TransaksiPengepul::whereIn('id_transaksi_pengepul', $data['transaksi_ids'])
            ->where('pengepul_id', $pengepulId)
            ->where('sudah_disetor', false)
            ->get();

        if ($transaksi->isEmpty()) {
            return back()->with('error', 'Tidak ada transaksi valid yang dipilih.');
        }

        $totalNilaiNasabah   = $transaksi->sum('nilai_idr');
        $totalSelisih        = $transaksi->sum('selisih_total');
        $totalKomisiPengepul = $transaksi->sum('transaksi_pengepul_komisi_pengepul');
        $totalBagianAdmin    = $transaksi->sum('bagian_admin');
        $totalDisetor        = $totalNilaiNasabah + $totalBagianAdmin;

        $setoran = SetoranPengepul::create([
            'pengepul_id'             => $pengepulId,
            'total_nilai_nasabah'     => $totalNilaiNasabah,
            'total_selisih'           => $totalSelisih,
            'total_komisi_pengepul'   => $totalKomisiPengepul,
            'total_bagian_admin'      => $totalBagianAdmin,
            'total_disetor'           => $totalDisetor,
            'transaksi_ids'           => json_encode($transaksi->pluck('id_transaksi_pengepul')->toArray()),
            'setoran_pengepul_status' => 'menunggu',
            'setoran_pengepul_gambar' => $request->hasFile('foto_dokumentasi') ? $request->file('foto_dokumentasi')->store('dokumentasi_setoran', 'public') : null,
        ]);

        TransaksiPengepul::whereIn('id_transaksi_pengepul', $transaksi->pluck('id_transaksi_pengepul'))->update(['sudah_disetor' => true]);

        return redirect()->route('pengepul.dashboard')
            ->with('success', 'Setoran berhasil dibuat! Total disetor ke admin: Rp ' . number_format($totalDisetor, 0, ',', '.') . '. Menunggu verifikasi admin.');
    }

    /**
     * Laporan bulanan pengepul
     */
    public function laporan(Request $request)
    {
        $pengepulId = session('user_id');
        $pengepul   = Pengepul::findOrFail($pengepulId);

        $bulan = $request->query('bulan', now()->format('m'));
        $tahun = $request->query('tahun', now()->format('Y'));

        $transaksi = $pengepul->transaksi()
            ->with(['nasabah', 'sampah'])
            ->whereMonth('transaksi_pengepul_tanggal', $bulan)
            ->whereYear('transaksi_pengepul_tanggal', $tahun)
            ->orderBy('transaksi_pengepul_tanggal')
            ->get();

        $totalBerat  = $transaksi->sum('berat_kg');
        $totalNilai  = $transaksi->sum('nilai_idr');
        $totalKomisi = $transaksi->sum('transaksi_pengepul_komisi_pengepul');

        if ($request->query('cetak') == '1') {
            return view('pengepul.laporan', compact(
                'pengepul', 'transaksi', 'bulan', 'tahun', 'totalBerat', 'totalNilai', 'totalKomisi'
            ));
        }

        return redirect()->route('pengepul.dashboard')
            ->with('_laporan', compact('transaksi', 'bulan', 'tahun', 'totalBerat', 'totalNilai', 'totalKomisi'));
    }

    /**
     * Update lokasi GPS pengepul
     */
    public function updateLokasi(Request $request)
    {
        $data = $request->validate([
            'latitude'  => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $pengepulId = session('user_id');
        $pengepul   = Pengepul::findOrFail($pengepulId);

        $pengepul->pengepul_latitude  = $data['latitude'];
        $pengepul->pengepul_longitude = $data['longitude'];
        $pengepul->save();

        return redirect()->route('pengepul.dashboard')
            ->with('success', 'Lokasi GPS Anda berhasil diperbarui ke: ' . $data['latitude'] . ', ' . $data['longitude']);
    }

    /**
     * Tampilkan halaman pilihan profil pengepul (passwordless)
     */
    public function showPilihPengepul()
    {
        $pengepuls = Pengepul::where('pengepul_status_aktif', 'aktif')->orderBy('pengepul_nama')->get();
        return view('pengepul.pilih', compact('pengepuls'));
    }

    /**
     * Pilih pengepul untuk masuk ke session
     */
    public function selectPengepul($id)
    {
        $pengepul = Pengepul::where('pengepul_status_aktif', 'aktif')->findOrFail($id);
        session([
            'user_id'   => $pengepul->id_pengepul,
            'user_type' => 'pengepul',
        ]);

        return redirect()->route('pengepul.dashboard')->with('success', 'Berhasil masuk sebagai Pengepul: ' . $pengepul->pengepul_nama);
    }

    /**
     * Tampilkan form edit transaksi
     */
    public function editSetor($id)
    {
        $pengepulId = session('user_id');
        $transaksi  = TransaksiPengepul::where('pengepul_id', $pengepulId)->findOrFail($id);

        if ($transaksi->sudah_disetor) {
            return redirect()->route('pengepul.dashboard')->with('error', 'Transaksi yang sudah dikelompokkan ke setoran tidak dapat diedit.');
        }

        $sampahs = Sampah::orderBy('sampah_nama')->get();
        return view('pengepul.edit_transaksi', compact('transaksi', 'sampahs'));
    }

    /**
     * Proses update transaksi + penyesuaian finansial nasabah
     */
    public function updateSetor(Request $request, $id)
    {
        $data = $request->validate([
            'id_sampah'        => 'required|integer|exists:sampah,id_sampah',
            'berat_kg'         => 'required|numeric|min:0.01',
            'keterangan'       => 'nullable|string|max:255',
            'foto_dokumentasi' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $pengepulId = session('user_id');
        $pengepul   = Pengepul::findOrFail($pengepulId);
        $transaksi  = TransaksiPengepul::where('pengepul_id', $pengepulId)->findOrFail($id);

        if ($transaksi->sudah_disetor) {
            return redirect()->route('pengepul.dashboard')->with('error', 'Transaksi yang sudah dikelompokkan ke setoran tidak dapat diedit.');
        }

        $nasabahId = $transaksi->nasabah_id;
        $nasabah   = Nasabah::findOrFail($nasabahId);
        $sampah    = Sampah::findOrFail($data['id_sampah']);

        // Nilai lama
        $oldNilaiIdr = $transaksi->nilai_idr;
        $oldBeratKg  = $transaksi->berat_kg;

        // Kalkulasi baru
        $hargaBeliKg  = $sampah->sampah_harga_kg;
        $hargaPasarKg = $sampah->sampah_harga_pasar ?? $hargaBeliKg;
        $beratKg      = $data['berat_kg'];

        $nilaiIdr     = round($hargaBeliKg  * $beratKg, 2);
        $nilaiPasar   = round($hargaPasarKg * $beratKg, 2);
        $selisihTotal = round($nilaiPasar - $nilaiIdr, 2);

        $komisiPersen   = $pengepul->pengepul_komisi_persen ?? 50;
        $komisiPengepul = round($selisihTotal * $komisiPersen / 100, 2);
        $bagianAdmin    = round($selisihTotal - $komisiPengepul, 2);

        // 1. Sesuaikan saldo Nasabah
        $nasabah->nasabah_saldo = round($nasabah->nasabah_saldo - $oldNilaiIdr + $nilaiIdr, 2);
        $nasabah->save();

        // 2. Sesuaikan Tabungan
        $tabungan = Tabungan::where('id_nasabah', $nasabahId)->first();
        if ($tabungan) {
            $tabungan->tabungan_total_setor = round($tabungan->tabungan_total_setor - $oldNilaiIdr + $nilaiIdr, 2);
            $tabungan->tabungan_saldo_akhir = round($tabungan->tabungan_saldo_akhir - $oldNilaiIdr + $nilaiIdr, 2);
            $tabungan->tabungan_tgl_update   = now();
            $tabungan->save();
        }

        // 3. Sesuaikan Gamifikasi
        $oldPoin    = round($oldBeratKg * 10);
        $newPoin    = round($beratKg * 10);
        $gamifikasi = Gamifikasi::where('id_nasabah', $nasabahId)->first();
        if ($gamifikasi) {
            $gamifikasi->poin_diperoleh = max(0, $gamifikasi->poin_diperoleh - $oldPoin + $newPoin);
            $gamifikasi->total_poin     = max(0, $gamifikasi->total_poin - $oldPoin + $newPoin);
            $totalPoin = $gamifikasi->total_poin;
            if ($totalPoin >= 500) {
                $gamifikasi->level_nasabah = 'Bintang';
                $gamifikasi->badge         = 'Penyetor Bintang';
            } elseif ($totalPoin >= 100) {
                $gamifikasi->level_nasabah = 'Aktif';
                $gamifikasi->badge         = 'Penyetor Konsisten';
            } else {
                $gamifikasi->level_nasabah = 'Pemula';
                $gamifikasi->badge         = 'Eco Starter';
            }
            $gamifikasi->tanggal_update = now();
            $gamifikasi->save();
        }

        // 4. Update transaksi
        $transaksi->id_sampah                          = $data['id_sampah'];
        $transaksi->berat_kg                           = $beratKg;
        $transaksi->harga_beli_kg                      = $hargaBeliKg;
        $transaksi->harga_pasar_kg                     = $hargaPasarKg;
        $transaksi->nilai_idr                          = $nilaiIdr;
        $transaksi->selisih_total                      = $selisihTotal;
        $transaksi->transaksi_pengepul_komisi_pengepul = $komisiPengepul;
        $transaksi->bagian_admin                       = $bagianAdmin;
        $transaksi->transaksi_pengepul_keterangan      = $data['keterangan'] ?? $transaksi->transaksi_pengepul_keterangan;

        if ($request->hasFile('foto_dokumentasi')) {
            $transaksi->transaksi_pengepul_gambar = $request->file('foto_dokumentasi')->store('dokumentasi_timbangan', 'public');
        }

        $transaksi->save();

        return redirect()->route('pengepul.dashboard')->with('success', 'Transaksi berhasil diperbarui dan saldo nasabah telah disesuaikan.');
    }

    /**
     * Hapus transaksi + penyesuaian finansial nasabah
     */
    public function deleteSetor($id)
    {
        $pengepulId = session('user_id');
        $transaksi  = TransaksiPengepul::where('pengepul_id', $pengepulId)->findOrFail($id);

        if ($transaksi->sudah_disetor) {
            return redirect()->route('pengepul.dashboard')->with('error', 'Transaksi yang sudah dikelompokkan ke setoran tidak dapat dihapus.');
        }

        $nasabahId   = $transaksi->nasabah_id;
        $nasabah     = Nasabah::findOrFail($nasabahId);
        $oldNilaiIdr = $transaksi->nilai_idr;
        $oldBeratKg  = $transaksi->berat_kg;

        // 1. Kurangi saldo nasabah
        $nasabah->nasabah_saldo = max(0, round($nasabah->nasabah_saldo - $oldNilaiIdr, 2));
        $nasabah->save();

        // 2. Kurangi di tabungan
        $tabungan = Tabungan::where('id_nasabah', $nasabahId)->first();
        if ($tabungan) {
            $tabungan->tabungan_total_setor = max(0, round($tabungan->tabungan_total_setor - $oldNilaiIdr, 2));
            $tabungan->tabungan_saldo_akhir = max(0, round($tabungan->tabungan_saldo_akhir - $oldNilaiIdr, 2));
            $tabungan->tabungan_tgl_update  = now();
            $tabungan->save();
        }

        // 3. Kurangi poin gamifikasi
        $oldPoin    = round($oldBeratKg * 10);
        $gamifikasi = Gamifikasi::where('id_nasabah', $nasabahId)->first();
        if ($gamifikasi) {
            $gamifikasi->poin_diperoleh = max(0, $gamifikasi->poin_diperoleh - $oldPoin);
            $gamifikasi->total_poin     = max(0, $gamifikasi->total_poin - $oldPoin);
            $totalPoin = $gamifikasi->total_poin;
            if ($totalPoin >= 500) {
                $gamifikasi->level_nasabah = 'Bintang';
                $gamifikasi->badge         = 'Penyetor Bintang';
            } elseif ($totalPoin >= 100) {
                $gamifikasi->level_nasabah = 'Aktif';
                $gamifikasi->badge         = 'Penyetor Konsisten';
            } else {
                $gamifikasi->level_nasabah = 'Pemula';
                $gamifikasi->badge         = 'Eco Starter';
            }
            $gamifikasi->tanggal_update = now();
            $gamifikasi->save();
        }

        // 4. Hapus record transaksi
        $transaksi->delete();

        return redirect()->route('pengepul.dashboard')->with('success', 'Transaksi berhasil dihapus dan saldo nasabah telah disesuaikan.');
    }
}
