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
                    'nama_hadiah' => 'Sabun Cuci Piring Cair',
                    'poin_butuh' => 100,
                    'stok' => 50,
                    'keterangan' => 'Sabun pembersih ramah lingkungan ukuran 400ml.',
                ],
                [
                    'nama_hadiah' => 'Tas Totebag Kanvas Eco',
                    'poin_butuh' => 150,
                    'stok' => 30,
                    'keterangan' => 'Tas belanja kanvas tebal reusable untuk kurangi plastik.',
                ],
                [
                    'nama_hadiah' => 'Minyak Goreng 1L',
                    'poin_butuh' => 300,
                    'stok' => 20,
                    'keterangan' => 'Minyak goreng kualitas premium kemasan botol 1 liter.',
                ],
                [
                    'nama_hadiah' => 'Tumbler Kaca Eco',
                    'poin_butuh' => 400,
                    'stok' => 15,
                    'keterangan' => 'Botol air minum kaca dilapisi pelindung silikon anti-slip.',
                ],
                [
                    'nama_hadiah' => 'Paket Sembako Premium',
                    'poin_butuh' => 800,
                    'stok' => 10,
                    'keterangan' => 'Paket sembako berisi beras premium 5kg, gula pasir 1kg, dan teh celup.',
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

        $totalPoinButuh = $hadiah->poin_butuh * $data['jumlah'];

        // 1. Validasi stok cukup
        if ($hadiah->stok < $data['jumlah']) {
            return back()->with('error', 'Stok hadiah "' . $hadiah->nama_hadiah . '" tidak mencukupi saat ini.');
        }

        // 2. Validasi kecukupan poin diperoleh
        $gamifikasi = $nasabah->gamifikasi;
        if (!$gamifikasi || $gamifikasi->poin_diperoleh < $totalPoinButuh) {
            $poinMilik = $gamifikasi ? $gamifikasi->poin_diperoleh : 0;
            return back()->with('error', 'Eco Poin Anda tidak mencukupi. Dibutuhkan ' . number_format($totalPoinButuh, 0, ',', '.') . ' Poin, milik Anda: ' . number_format($poinMilik, 0, ',', '.') . ' Poin.');
        }

        // 3. Kurangi poin diperoleh (tidak mengurangi total_poin lifetime)
        $gamifikasi->poin_diperoleh -= $totalPoinButuh;
        $gamifikasi->tanggal_update = now();
        $gamifikasi->save();

        // 4. Kurangi stok barang
        $hadiah->stok -= $data['jumlah'];
        $hadiah->save();

        // 5. Buat transaksi penukaran
        PenukaranReward::create([
            'id_nasabah'         => $nasabahId,
            'id_hadiah'          => $hadiah->id_hadiah,
            'jumlah'             => $data['jumlah'],
            'total_poin_ditukar' => $totalPoinButuh,
            'status'             => 'menunggu',
            'tanggal_tukar'      => now()->toDateString(),
        ]);

        return back()->with('success', 'Penukaran ' . $data['jumlah'] . 'x ' . $hadiah->nama_hadiah . ' seharga ' . number_format($totalPoinButuh, 0, ',', '.') . ' Eco Poin berhasil diajukan. Silakan ambil barang di kantor Bank Sampah dengan menunjukkan akun Anda.');
    }

    /**
     * Admin – Daftar penukaran poin nasabah & Katalog Hadiah CRUD.
     */
    public function adminIndex()
    {
        $this->pastikanHadiahDefault();

        // Penukaran berdasarkan status
        $menunggu = PenukaranReward::with(['nasabah', 'hadiah'])
            ->where('status', 'menunggu')
            ->orderByDesc('created_at')
            ->get();

        $diambil = PenukaranReward::with(['nasabah', 'hadiah', 'admin'])
            ->where('status', 'diambil')
            ->orderByDesc('created_at')
            ->get();

        $ditolak = PenukaranReward::with(['nasabah', 'hadiah', 'admin'])
            ->where('status', 'ditolak')
            ->orderByDesc('created_at')
            ->get();

        // Daftar katalog hadiah untuk CRUD
        $hadiahs = Hadiah::orderBy('nama_hadiah')->get();

        return view('admin.reward.index', compact('menunggu', 'diambil', 'ditolak', 'hadiahs'));
    }

    /**
     * Admin – Menyetujui penyerahan barang fisik.
     */
    public function adminApprove($id)
    {
        $penukaran = PenukaranReward::findOrFail($id);

        if ($penukaran->status !== 'menunggu') {
            return back()->with('error', 'Pengajuan penukaran ini sudah diproses sebelumnya.');
        }

        $penukaran->status   = 'diambil';
        $penukaran->id_admin = session('user_id');
        $penukaran->save();

        return back()->with('success', 'Penukaran barang berhasil disetujui. Barang telah diserahkan secara fisik kepada nasabah.');
    }

    /**
     * Admin – Menolak pengajuan penukaran poin (mengembalikan poin & stok).
     */
    public function adminReject(Request $request, $id)
    {
        $penukaran = PenukaranReward::findOrFail($id);

        if ($penukaran->status !== 'menunggu') {
            return back()->with('error', 'Pengajuan penukaran ini sudah diproses sebelumnya.');
        }

        $penukaran->status   = 'ditolak';
        $penukaran->catatan  = $request->input('catatan', 'Ditolak oleh admin.');
        $penukaran->id_admin = session('user_id');
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
        $hadiah->stok += $penukaran->jumlah;
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
            'nama_hadiah' => 'required|string|max:100|unique:hadiah,nama_hadiah',
            'poin_butuh'  => 'required|integer|min:1',
            'stok'        => 'required|integer|min:0',
            'keterangan'  => 'nullable|string',
        ]);

        Hadiah::create([
            'nama_hadiah' => $data['nama_hadiah'],
            'poin_butuh'  => $data['poin_butuh'],
            'stok'        => $data['stok'],
            'keterangan'  => $data['keterangan'] ?? null,
        ]);

        return back()->with('success', 'Hadiah "' . $data['nama_hadiah'] . '" berhasil ditambahkan ke katalog.');
    }

    /**
     * Admin – Update detail hadiah di katalog.
     */
    public function updateHadiah(Request $request, $id)
    {
        $hadiah = Hadiah::findOrFail($id);

        $data = $request->validate([
            'nama_hadiah' => 'required|string|max:100|unique:hadiah,nama_hadiah,' . $id . ',id_hadiah',
            'poin_butuh'  => 'required|integer|min:1',
            'stok'        => 'required|integer|min:0',
            'keterangan'  => 'nullable|string',
        ]);

        $hadiah->update([
            'nama_hadiah' => $data['nama_hadiah'],
            'poin_butuh'  => $data['poin_butuh'],
            'stok'        => $data['stok'],
            'keterangan'  => $data['keterangan'] ?? null,
        ]);

        return back()->with('success', 'Detail hadiah "' . $hadiah->nama_hadiah . '" berhasil diperbarui.');
    }

    /**
     * Admin – Hapus hadiah dari katalog.
     */
    public function deleteHadiah($id)
    {
        $hadiah = Hadiah::findOrFail($id);
        $nama   = $hadiah->nama_hadiah;
        $hadiah->delete();

        return back()->with('success', 'Hadiah "' . $nama . '" berhasil dihapus dari katalog.');
    }
}
