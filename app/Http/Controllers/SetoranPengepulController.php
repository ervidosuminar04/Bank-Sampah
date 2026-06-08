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

        return view('admin.setoran_pengepul.show', compact('setoran', 'transaksi'));
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
}
