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
        $pengepul   = Pengepul::findOrFail($pengepulId);

        // Statistik umum
        $totalTransaksi   = $pengepul->transaksi()->count();
        $totalBeratKg     = $pengepul->transaksi()->sum('berat_kg');
        $totalNilaiRupiah = $pengepul->transaksi()->sum('nilai_idr');
        $totalKomisi      = $pengepul->transaksi()->sum('komisi_pengepul');

        // Transaksi bulan ini (untuk tab Dashboard)
        $transaksibulanIni = $pengepul->transaksi()
            ->with(['nasabah', 'sampah'])
            ->whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->orderByDesc('tanggal')
            ->get();

        // Daftar nasabah aktif untuk dropdown timbangan
        $nasabahs = Nasabah::where('nasabah_status', 'aktif')
            ->orderBy('nasabah_nama')
            ->get();

        // Daftar jenis sampah untuk dropdown timbangan
        $sampahs = Sampah::orderBy('sampah_name')->get();

        // Transaksi yang belum disetor (untuk tab Setoran)
        $transaksiBelumDisetor = $pengepul->transaksiBelumDisetor()
            ->with(['nasabah', 'sampah'])
            ->orderByDesc('tanggal')
            ->get();

        $totalBelumDisetor         = $transaksiBelumDisetor->sum('nilai_idr');
        $totalSelisihBelumDisetor  = $transaksiBelumDisetor->sum('selisih_total');
        $totalKomisiBelum          = $transaksiBelumDisetor->sum('komisi_pengepul');
        $totalAdminBelum           = $transaksiBelumDisetor->sum('bagian_admin');
        $totalHarusDisetor         = $totalBelumDisetor + $totalAdminBelum;

        // Riwayat setoran
        $riwayatSetoran = $pengepul->setoran()
            ->orderByDesc('created_at')
            ->get();

        // Data laporan – diload saat filter dikirim via query param
        $laporanBulan     = $request->query('bulan', now()->format('m'));
        $laporanTahun     = $request->query('tahun', now()->format('Y'));
        $laporanTransaksi = $pengepul->transaksi()
            ->with(['nasabah', 'sampah'])
            ->whereMonth('tanggal', $laporanBulan)
            ->whereYear('tanggal', $laporanTahun)
            ->orderBy('tanggal')
            ->get();
        $laporanTotalBerat = $laporanTransaksi->sum('berat_kg');
        $laporanTotalNilai = $laporanTransaksi->sum('nilai_idr');
        $laporanTotalKomisi = $laporanTransaksi->sum('komisi_pengepul');

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
     * – menghitung nilai_idr (saldo nasabah), selisih, komisi pengepul, bagian admin
     */
    public function storeSetor(Request $request)
    {
        $data = $request->validate([
            'nasabah_id'  => 'required|integer|exists:nasabah,id_nasabah',
            'id_sampah'   => 'required|integer|exists:sampah,id_sampah',
            'berat_kg'    => 'required|numeric|min:0.01',
            'keterangan'  => 'nullable|string|max:255',
        ]);

        $pengepulId = session('user_id');
        $pengepul   = Pengepul::findOrFail($pengepulId);
        $nasabah    = Nasabah::find($data['nasabah_id']);
        $sampah     = Sampah::find($data['id_sampah']);

        // ── Kalkulasi finansial ──────────────────────────────────────────
        $hargaBeliKg  = $sampah->sampah_harga_kg;                         // harga ke nasabah
        $hargaPasarKg = $sampah->sampah_harga_pasar ?? $hargaBeliKg;      // harga jual ke luar
        $beratKg      = $data['berat_kg'];

        $nilaiIdr     = round($hargaBeliKg  * $beratKg, 2);               // saldo ke nasabah
        $nilaiPasar   = round($hargaPasarKg * $beratKg, 2);               // nilai jual ke luar
        $selisihTotal = round($nilaiPasar - $nilaiIdr, 2);                // margin kotor

        $komisiPersen    = $pengepul->komisi_persen ?? 50;
        $komisiPengepul  = round($selisihTotal * $komisiPersen / 100, 2); // bagian pengepul
        $bagianAdmin     = round($selisihTotal - $komisiPengepul, 2);     // bagian admin

        // ── Simpan transaksi pengepul ────────────────────────────────────
        TransaksiPengepul::create([
            'pengepul_id'     => $pengepulId,
            'nasabah_id'      => $data['nasabah_id'],
            'id_sampah'       => $data['id_sampah'],
            'berat_kg'        => $beratKg,
            'harga_beli_kg'   => $hargaBeliKg,
            'harga_pasar_kg'  => $hargaPasarKg,
            'nilai_idr'       => $nilaiIdr,
            'selisih_total'   => $selisihTotal,
            'komisi_pengepul' => $komisiPengepul,
            'bagian_admin'    => $bagianAdmin,
            'sudah_disetor'   => false,
            'tanggal'         => now()->toDateString(),
            'keterangan'      => $data['keterangan'] ?? 'Setor ' . $sampah->sampah_name . ' ' . $beratKg . ' kg via pengepul',
        ]);

        // ── Update tabungan nasabah (hanya nilai harga beli) ─────────────
        $tabungan = Tabungan::where('id_nasabah', $data['nasabah_id'])->first();
        if ($tabungan) {
            $tabungan->tabungan_total_setor += $nilaiIdr;
            $tabungan->tabungan_saldo_akhir += $nilaiIdr;
            $tabungan->tabungan_tgl_update   = now();
            $tabungan->save();
        }

        // ── Update saldo denormalisasi di tabel nasabah ──────────────────
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
     * Pengepul membuat setoran ke admin (batch dari transaksi yang belum disetor)
     */
    public function storeSetoran(Request $request)
    {
        $data = $request->validate([
            'transaksi_ids'   => 'required|array|min:1',
            'transaksi_ids.*' => 'integer|exists:transaksi_pengepul,id',
        ]);

        $pengepulId = session('user_id');
        $pengepul   = Pengepul::findOrFail($pengepulId);

        // Ambil transaksi yang dipilih (harus milik pengepul ini & belum disetor)
        $transaksi = TransaksiPengepul::whereIn('id', $data['transaksi_ids'])
            ->where('pengepul_id', $pengepulId)
            ->where('sudah_disetor', false)
            ->get();

        if ($transaksi->isEmpty()) {
            return back()->with('error', 'Tidak ada transaksi valid yang dipilih.');
        }

        $totalNilaiNasabah    = $transaksi->sum('nilai_idr');
        $totalSelisih         = $transaksi->sum('selisih_total');
        $totalKomisiPengepul  = $transaksi->sum('komisi_pengepul');
        $totalBagianAdmin     = $transaksi->sum('bagian_admin');
        $totalDisetor         = $totalNilaiNasabah + $totalBagianAdmin;

        // Buat record setoran
        $setoran = SetoranPengepul::create([
            'pengepul_id'           => $pengepulId,
            'total_nilai_nasabah'   => $totalNilaiNasabah,
            'total_selisih'         => $totalSelisih,
            'total_komisi_pengepul' => $totalKomisiPengepul,
            'total_bagian_admin'    => $totalBagianAdmin,
            'total_disetor'         => $totalDisetor,
            'transaksi_ids'         => $transaksi->pluck('id')->toArray(),
            'status'                => 'menunggu',
        ]);

        // Tandai semua transaksi sebagai sudah disetor
        TransaksiPengepul::whereIn('id', $transaksi->pluck('id'))->update(['sudah_disetor' => true]);

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
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->orderBy('tanggal')
            ->get();

        $totalBerat  = $transaksi->sum('berat_kg');
        $totalNilai  = $transaksi->sum('nilai_idr');
        $totalKomisi = $transaksi->sum('komisi_pengepul');

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

        $pengepul->latitude  = $data['latitude'];
        $pengepul->longitude = $data['longitude'];
        $pengepul->save();

        return redirect()->route('pengepul.dashboard')
            ->with('success', 'Lokasi GPS Anda berhasil diperbarui ke: ' . $data['latitude'] . ', ' . $data['longitude']);
    }
}
