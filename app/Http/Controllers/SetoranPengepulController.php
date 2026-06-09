<?php

namespace App\Http\Controllers;

use App\Models\SetoranPengepul;
use App\Models\TransaksiPengepul;
use Illuminate\Http\Request;

class SetoranPengepulController extends Controller
{
    /**
     * Daftar semua setoran (Admin).
     */
    public function index()
    {
        $setoranMenunggu = SetoranPengepul::with('pengepul')
            ->where('setoran_pengepul_status', 'menunggu')
            ->orderByDesc('id_setoran_pengepul')
            ->get();

        $setoranTerverifikasi = SetoranPengepul::with('pengepul')
            ->where('setoran_pengepul_status', 'terverifikasi')
            ->orderByDesc('id_setoran_pengepul')
            ->get();

        $setoranDitolak = SetoranPengepul::with('pengepul')
            ->where('setoran_pengepul_status', 'ditolak')
            ->orderByDesc('id_setoran_pengepul')
            ->get();

        $totalPendapatanAdmin = SetoranPengepul::where('setoran_pengepul_status', 'terverifikasi')
            ->sum('total_bagian_admin');

        return view('admin.setoran_pengepul.index', compact(
            'setoranMenunggu',
            'setoranTerverifikasi',
            'setoranDitolak',
            'totalPendapatanAdmin'
        ));
    }

    /**
     * Detail satu setoran + daftar transaksi terkait.
     */
    public function show($id)
    {
        $setoran = SetoranPengepul::with('pengepul')->findOrFail($id);

        $ids = is_array($setoran->transaksi_ids)
            ? $setoran->transaksi_ids
            : json_decode($setoran->transaksi_ids, true) ?? [];

        $transaksi = TransaksiPengepul::with(['nasabah', 'sampah'])
            ->whereIn('id_transaksi_pengepul', $ids)
            ->get();

        $allSampah = \App\Models\Sampah::orderBy('sampah_nama')->get();

        return view('admin.setoran_pengepul.show', compact('setoran', 'transaksi', 'allSampah'));
    }

    /**
     * Admin verifikasi setoran → status = terverifikasi.
     */
    public function verify(Request $request, $id)
    {
        $setoran = SetoranPengepul::findOrFail($id);

        if ($setoran->setoran_pengepul_status !== 'menunggu') {
            return back()->with('error', 'Setoran ini sudah diproses sebelumnya.');
        }

        $setoran->setoran_pengepul_status = 'terverifikasi';
        $setoran->id_admin = session('user_id');
        $setoran->catatan  = $request->input('catatan');
        $setoran->save();

        return back()->with('success', 'Setoran berhasil diverifikasi. Pendapatan admin: Rp ' . number_format($setoran->total_bagian_admin, 0, ',', '.'));
    }

    /**
     * Admin menolak setoran → status = ditolak, reset sudah_disetor transaksi terkait.
     */
    public function reject(Request $request, $id)
    {
        $setoran = SetoranPengepul::findOrFail($id);

        if ($setoran->setoran_pengepul_status !== 'menunggu') {
            return back()->with('error', 'Setoran ini sudah diproses sebelumnya.');
        }

        $setoran->setoran_pengepul_status = 'ditolak';
        $setoran->id_admin = session('user_id');
        $setoran->catatan  = $request->input('catatan', 'Ditolak oleh admin.');
        $setoran->save();

        // Reset transaksi terkait agar bisa disetor ulang
        $ids = is_array($setoran->transaksi_ids)
            ? $setoran->transaksi_ids
            : json_decode($setoran->transaksi_ids, true) ?? [];

        TransaksiPengepul::whereIn('id_transaksi_pengepul', $ids)
            ->update(['sudah_disetor' => false]);

        return back()->with('success', 'Setoran telah ditolak. Transaksi terkait dapat disetor ulang oleh pengepul.');
    }

    /**
     * Admin update individual transaction in a Setoran Pengepul.
     */
    public function updateTransaksi(Request $request, $id)
    {
        $data = $request->validate([
            'id_sampah'  => 'required|integer|exists:sampah,id_sampah',
            'berat_kg'   => 'required|numeric|min:0.01',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $transaksi = TransaksiPengepul::findOrFail($id);
        
        $nasabahId = $transaksi->nasabah_id;
        $nasabah   = \App\Models\Nasabah::findOrFail($nasabahId);
        $sampah    = \App\Models\Sampah::findOrFail($data['id_sampah']);
        
        // Find the related Pengepul to get their commission percentage
        $pengepul = \App\Models\Pengepul::findOrFail($transaksi->pengepul_id);

        // Values before editing
        $oldNilaiIdr        = $transaksi->nilai_idr;
        $oldBeratKg         = $transaksi->berat_kg;
        $oldSelisihTotal    = $transaksi->selisih_total;
        $oldKomisiPengepul  = $transaksi->transaksi_pengepul_komisi_pengepul;
        $oldBagianAdmin     = $transaksi->bagian_admin;
        $oldTotalDisetor    = $oldNilaiIdr + $oldBagianAdmin;

        // Recalculations
        $hargaBeliKg  = $sampah->sampah_harga_kg;
        $hargaPasarKg = $sampah->sampah_harga_pasar ?? $hargaBeliKg;
        $beratKg      = $data['berat_kg'];

        $nilaiIdr     = round($hargaBeliKg  * $beratKg, 2);
        $nilaiPasar   = round($hargaPasarKg * $beratKg, 2);
        $selisihTotal = round($nilaiPasar - $nilaiIdr, 2);

        $komisiPersen   = $pengepul->pengepul_komisi_persen ?? 50;
        $komisiPengepul = round($selisihTotal * $komisiPersen / 100, 2);
        $bagianAdmin    = round($selisihTotal - $komisiPengepul, 2);

        // 1. Adjust Nasabah's balance
        $nasabah->nasabah_saldo = round($nasabah->nasabah_saldo - $oldNilaiIdr + $nilaiIdr, 2);
        $nasabah->save();

        // 2. Adjust Nasabah's Tabungan
        $tabungan = \App\Models\Tabungan::where('id_nasabah', $nasabahId)->first();
        if ($tabungan) {
            $tabungan->tabungan_total_setor = round($tabungan->tabungan_total_setor - $oldNilaiIdr + $nilaiIdr, 2);
            $tabungan->tabungan_saldo_akhir = round($tabungan->tabungan_saldo_akhir - $oldNilaiIdr + $nilaiIdr, 2);
            $tabungan->tabungan_tgl_update   = now();
            $tabungan->save();
        }

        // 3. Adjust Nasabah's Gamifikasi points (1 kg = 10 points)
        $oldPoin    = round($oldBeratKg * 10);
        $newPoin    = round($beratKg * 10);
        $gamifikasi = \App\Models\Gamifikasi::where('id_nasabah', $nasabahId)->first();
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

        // 4. Update the individual transaction
        $transaksi->id_sampah                          = $data['id_sampah'];
        $transaksi->berat_kg                           = $beratKg;
        $transaksi->harga_beli_kg                      = $hargaBeliKg;
        $transaksi->harga_pasar_kg                     = $hargaPasarKg;
        $transaksi->nilai_idr                          = $nilaiIdr;
        $transaksi->selisih_total                      = $selisihTotal;
        $transaksi->transaksi_pengepul_komisi_pengepul = $komisiPengepul;
        $transaksi->bagian_admin                        = $bagianAdmin;
        $transaksi->transaksi_pengepul_keterangan       = $data['keterangan'] ?? ('Setor ' . $sampah->sampah_name . ' ' . $beratKg . ' kg via pengepul');
        $transaksi->save();

        // 5. Adjust the parent Setoran Pengepul if it is grouped inside one
        $setoran = SetoranPengepul::all()->filter(function ($s) use ($id) {
            $ids = is_array($s->transaksi_ids) ? $s->transaksi_ids : json_decode($s->transaksi_ids, true);
            return is_array($ids) && in_array($id, $ids);
        })->first();

        if ($setoran) {
            $setoran->total_nilai_nasabah   = round($setoran->total_nilai_nasabah - $oldNilaiIdr + $nilaiIdr, 2);
            $setoran->total_selisih          = round($setoran->total_selisih - $oldSelisihTotal + $selisihTotal, 2);
            $setoran->total_komisi_pengepul = round($setoran->total_komisi_pengepul - $oldKomisiPengepul + $komisiPengepul, 2);
            $setoran->total_bagian_admin    = round($setoran->total_bagian_admin - $oldBagianAdmin + $bagianAdmin, 2);
            $setoran->total_disetor         = round($setoran->total_disetor - $oldTotalDisetor + ($nilaiIdr + $bagianAdmin), 2);
            $setoran->save();
        }

        return back()->with('success', 'Transaksi berhasil diperbarui dan semua perhitungan keuangan (nasabah & setoran) disesuaikan otomatis.');
    }
}
