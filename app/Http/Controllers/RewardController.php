<?php

namespace App\Http\Controllers;

use App\Models\Hadiah;
use App\Models\PenukaranReward;
use App\Models\Nasabah;
use App\Models\Gamifikasi;
use Illuminate\Http\Request;

class RewardController extends Controller
{
    /**
     * Memastikan data hadiah default tersedia di database (Seeding otomatis).
     */
    private function pastikanHadiahDefault()
    {
        if (Hadiah::count() === 0) {
            $defaults = [
                [
                    'hadiah_nama'        => 'Sabun Cuci Piring Cair',
                    'hadiah_poin_butuh'  => 100,
                    'hadiah_stok'        => 50,
                    'hadiah_keterangan'  => 'Sabun pembersih ramah lingkungan ukuran 400ml.',
                ],
                [
                    'hadiah_nama'        => 'Tas Totebag Kanvas Eco',
                    'hadiah_poin_butuh'  => 150,
                    'hadiah_stok'        => 30,
                    'hadiah_keterangan'  => 'Tas belanja kanvas tebal reusable untuk kurangi plastik.',
                ],
                [
                    'hadiah_nama'        => 'Minyak Goreng 1L',
                    'hadiah_poin_butuh'  => 300,
                    'hadiah_stok'        => 20,
                    'hadiah_keterangan'  => 'Minyak goreng kualitas premium kemasan botol 1 liter.',
                ],
                [
                    'hadiah_nama'        => 'Tumbler Kaca Eco',
                    'hadiah_poin_butuh'  => 400,
                    'hadiah_stok'        => 15,
                    'hadiah_keterangan'  => 'Botol air minum kaca dilapisi pelindung silikon anti-slip.',
                ],
                [
                    'hadiah_nama'        => 'Paket Sembako Premium',
                    'hadiah_poin_butuh'  => 800,
                    'hadiah_stok'        => 10,
                    'hadiah_keterangan'  => 'Paket sembako berisi beras premium 5kg, gula pasir 1kg, dan teh celup.',
                ],
            ];

            foreach ($defaults as $d) {
                Hadiah::create($d);
            }
        }
    }

    /**
     * Nasabah menukarkan Eco Poin dengan barang katalog.
     */
    public function tukarPoin(Request $request)
    {
        $data = $request->validate([
            'id_hadiah' => 'required|integer|exists:hadiah,id_hadiah',
            'jumlah'    => 'required|integer|min:1',
        ]);

        $nasabahId = session('user_id');
        $nasabah   = Nasabah::with('gamifikasi')->findOrFail($nasabahId);
        $hadiah    = Hadiah::findOrFail($data['id_hadiah']);

        $totalPoinButuh = $hadiah->hadiah_poin_butuh * $data['jumlah'];

        // 1. Validasi stok cukup
        if ($hadiah->hadiah_stok < $data['jumlah']) {
            return back()->with('error', 'Stok hadiah "' . $hadiah->hadiah_nama . '" tidak mencukupi saat ini.');
        }

        // 2. Validasi kecukupan poin diperoleh
        $gamifikasi = $nasabah->gamifikasi;
        if (!$gamifikasi || $gamifikasi->poin_diperoleh < $totalPoinButuh) {
            $poinMilik = $gamifikasi ? $gamifikasi->poin_diperoleh : 0;
            return back()->with('error', 'Eco Poin Anda tidak mencukupi. Dibutuhkan ' . number_format($totalPoinButuh, 0, ',', '.') . ' Poin, milik Anda: ' . number_format($poinMilik, 0, ',', '.') . ' Poin.');
        }

        // 3. Kurangi poin diperoleh
        $gamifikasi->poin_diperoleh -= $totalPoinButuh;
        $gamifikasi->tanggal_update  = now();
        $gamifikasi->save();

        // 4. Kurangi stok barang
        $hadiah->hadiah_stok -= $data['jumlah'];
        $hadiah->save();

        // 5. Buat transaksi penukaran
        PenukaranReward::create([
            'id_nasabah'         => $nasabahId,
            'id_hadiah'          => $hadiah->id_hadiah,
            'jumlah'             => $data['jumlah'],
            'total_poin_ditukar' => $totalPoinButuh,
            'penukaran_status'   => 'menunggu',
            'tanggal_tukar'      => now()->toDateString(),
        ]);

        return back()->with('success', 'Penukaran ' . $data['jumlah'] . 'x ' . $hadiah->hadiah_nama . ' seharga ' . number_format($totalPoinButuh, 0, ',', '.') . ' Eco Poin berhasil diajukan. Silakan ambil barang di kantor Bank Sampah dengan menunjukkan akun Anda.');
    }

    /**
     * Admin – Daftar penukaran poin nasabah & Katalog Hadiah CRUD.
     */
    public function adminIndex()
    {
        $this->pastikanHadiahDefault();

        $menunggu = PenukaranReward::with(['nasabah', 'hadiah'])
            ->where('penukaran_status', 'menunggu')
            ->orderByDesc('id_penukaran')
            ->get();

        $diambil = PenukaranReward::with(['nasabah', 'hadiah', 'admin'])
            ->where('penukaran_status', 'diambil')
            ->orderByDesc('id_penukaran')
            ->get();

        $ditolak = PenukaranReward::with(['nasabah', 'hadiah', 'admin'])
            ->where('penukaran_status', 'ditolak')
            ->orderByDesc('id_penukaran')
            ->get();

        $hadiahs = Hadiah::orderBy('hadiah_nama')->get();

        return view('admin.reward.index', compact('menunggu', 'diambil', 'ditolak', 'hadiahs'));
    }

    /**
     * Admin – Menyetujui penyerahan barang fisik.
     */
    public function adminApprove($id)
    {
        $penukaran = PenukaranReward::findOrFail($id);

        if ($penukaran->penukaran_status !== 'menunggu') {
            return back()->with('error', 'Pengajuan penukaran ini sudah diproses sebelumnya.');
        }

        $penukaran->penukaran_status = 'diambil';
        $penukaran->id_admin         = session('user_id');
        $penukaran->save();

        return back()->with('success', 'Penukaran barang berhasil disetujui. Barang telah diserahkan secara fisik kepada nasabah.');
    }

    /**
     * Admin – Menolak pengajuan penukaran poin (mengembalikan poin & stok).
     */
    public function adminReject(Request $request, $id)
    {
        $penukaran = PenukaranReward::findOrFail($id);

        if ($penukaran->penukaran_status !== 'menunggu') {
            return back()->with('error', 'Pengajuan penukaran ini sudah diproses sebelumnya.');
        }

        $penukaran->penukaran_status = 'ditolak';
        $penukaran->catatan          = $request->input('catatan', 'Ditolak oleh admin.');
        $penukaran->id_admin         = session('user_id');
        $penukaran->save();

        // 1. Kembalikan poin diperoleh nasabah
        $nasabah = Nasabah::with('gamifikasi')->findOrFail($penukaran->id_nasabah);
        if ($nasabah->gamifikasi) {
            $nasabah->gamifikasi->poin_diperoleh += $penukaran->total_poin_ditukar;
            $nasabah->gamifikasi->tanggal_update  = now();
            $nasabah->gamifikasi->save();
        }

        // 2. Kembalikan stok hadiah
        $hadiah = Hadiah::findOrFail($penukaran->id_hadiah);
        $hadiah->hadiah_stok += $penukaran->jumlah;
        $hadiah->save();

        return back()->with('success', 'Pengajuan penukaran ditolak. ' . number_format($penukaran->total_poin_ditukar, 0, ',', '.') . ' Eco Poin dan stok barang telah dikembalikan secara otomatis.');
    }

    // ============================================================
    // CRUD HADIAH OLEH ADMIN
    // ============================================================

    /**
     * Admin – Tambah hadiah baru ke katalog.
     */
    public function storeHadiah(Request $request)
    {
        $data = $request->validate([
            'hadiah_nama'       => 'required|string|max:100|unique:hadiah,hadiah_nama',
            'hadiah_poin_butuh' => 'required|integer|min:1',
            'hadiah_stok'       => 'required|integer|min:0',
            'hadiah_keterangan' => 'nullable|string',
        ]);

        Hadiah::create([
            'hadiah_nama'       => $data['hadiah_nama'],
            'hadiah_poin_butuh' => $data['hadiah_poin_butuh'],
            'hadiah_stok'       => $data['hadiah_stok'],
            'hadiah_keterangan' => $data['hadiah_keterangan'] ?? null,
        ]);

        return back()->with('success', 'Hadiah "' . $data['hadiah_nama'] . '" berhasil ditambahkan ke katalog.');
    }

    /**
     * Admin – Update detail hadiah di katalog.
     */
    public function updateHadiah(Request $request, $id)
    {
        $hadiah = Hadiah::findOrFail($id);

        $data = $request->validate([
            'hadiah_nama'       => 'required|string|max:100|unique:hadiah,hadiah_nama,' . $id . ',id_hadiah',
            'hadiah_poin_butuh' => 'required|integer|min:1',
            'hadiah_stok'       => 'required|integer|min:0',
            'hadiah_keterangan' => 'nullable|string',
        ]);

        $hadiah->update([
            'hadiah_nama'       => $data['hadiah_nama'],
            'hadiah_poin_butuh' => $data['hadiah_poin_butuh'],
            'hadiah_stok'       => $data['hadiah_stok'],
            'hadiah_keterangan' => $data['hadiah_keterangan'] ?? null,
        ]);

        return back()->with('success', 'Detail hadiah "' . $hadiah->hadiah_nama . '" berhasil diperbarui.');
    }

    /**
     * Admin – Hapus hadiah dari katalog.
     */
    public function deleteHadiah($id)
    {
        $hadiah = Hadiah::findOrFail($id);
        $nama   = $hadiah->hadiah_nama;
        $hadiah->delete();

        return back()->with('success', 'Hadiah "' . $nama . '" berhasil dihapus dari katalog.');
    }
}
