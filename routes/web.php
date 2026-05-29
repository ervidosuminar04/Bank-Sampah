<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PengepulController;
use App\Http\Controllers\SetoranPengepulController;
use App\Models\Admin;
use App\Models\Nasabah;
use App\Models\Tabungan;
use App\Models\Geolokasi;
use App\Models\Sampah;
use App\Models\TransaksiSetor;
use App\Models\TransaksiTarik;
use App\Models\SetoranPengepul;

// ============================================================
// Redirect home ke login
// ============================================================
Route::get('/', function () {
    return redirect()->route('login');
});

// ============================================================
// Authentication routes
// ============================================================
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
Route::post('/logout', [AuthController::class, 'logout']);

// ============================================================
// Dashboard (Admin & Nasabah) – protected by session
// ============================================================
Route::get('/dashboard', function () {
    $userId   = session('user_id');
    $userType = session('user_type');

    if (!$userId) {
        return redirect()->route('login');
    }

    // Redirect pengepul ke dashboard pengepul
    if ($userType === 'pengepul') {
        return redirect()->route('pengepul.dashboard');
    }

    $data = [
        'userId'   => $userId,
        'userType' => $userType,
    ];

    if ($userType === 'admin') {
        $admin        = Admin::find($userId);
        $data['user'] = $admin;

        $data['totalNasabah']             = Nasabah::where('nasabah_status', 'aktif')->count();
        $data['totalLokasi']              = Geolokasi::count();
        $data['totalBeratSampahBulanIni'] = TransaksiSetor::whereMonth('setor_tanggal', now()->month)
            ->whereYear('setor_tanggal', now()->year)
            ->sum('setor_berat_kg') +
            \App\Models\TransaksiPengepul::whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->sum('berat_kg');
        $data['totalKasMasuk']  = Tabungan::sum('tabungan_total_setor');
        $data['totalKasKeluar'] = TransaksiTarik::where('status', 'disetujui')->sum('tarik_jumlah');
        $data['setoranMenunggu'] = SetoranPengepul::where('status', 'menunggu')->count();
        $data['totalPendapatanAdmin'] = SetoranPengepul::where('status', 'terverifikasi')->sum('total_bagian_admin');
        $data['rewardsMenunggu'] = \App\Models\PenukaranReward::where('status', 'menunggu')->count();

        $data['activeNasabahs']     = Nasabah::where('nasabah_status', 'aktif')->orderBy('nasabah_nama', 'asc')->get();
        $data['pendingNasabahs']    = Nasabah::where('nasabah_status', 'pending')->orderBy('id_nasabah', 'desc')->get();
        $data['pendingTarikRequests'] = TransaksiTarik::with('nasabah')
            ->where('status', 'menunggu')
            ->orderBy('id_tarik', 'desc')
            ->get();
        $data['allSampah']    = Sampah::orderBy('sampah_name', 'asc')->get();
        $data['allGeolokasi'] = Geolokasi::orderBy('id_lokasi', 'desc')->get();
        $data['nasabahs']     = Nasabah::orderBy('id_nasabah', 'desc')->take(5)->get();
        $data['allPengepul']  = \App\Models\Pengepul::orderBy('nama')->get();

    } else {
        // Nasabah
        $nasabah = Nasabah::with(['tabungan', 'gamifikasi'])->find($userId);

        if ($nasabah && $nasabah->nasabah_status === 'pending') {
            \Illuminate\Support\Facades\Session::flush();
            return redirect()->route('login')
                ->withErrors(['username' => 'Akun Anda sedang dalam proses verifikasi oleh admin. Silakan coba lagi nanti.']);
        }

        if ($nasabah && !$nasabah->tabungan) {
            $nasabah->tabungan()->create([
                'tabungan_no_rekening' => 'BS-' . date('Ymd') . '-' . str_pad($nasabah->id_nasabah, 4, '0', STR_PAD_LEFT),
                'tabungan_total_setor' => 0,
                'tabungan_total_tarik' => 0,
                'tabungan_saldo_akhir' => 0,
                'tabungan_tgl_update'  => now(),
            ]);
            $nasabah->load('tabungan');
        }
        if ($nasabah && !$nasabah->gamifikasi) {
            $nasabah->gamifikasi()->create([
                'poin_diperoleh' => 0,
                'total_poin'     => 0,
                'level_nasabah'  => 'Pemula',
                'badge'          => 'Eco Starter',
                'tanggal_update' => now(),
            ]);
            $nasabah->load('gamifikasi');
        }

        $data['user']                = $nasabah;
        $data['totalSampahDisetorKg'] = TransaksiSetor::where('id_nasabah', $userId)->sum('setor_berat_kg') +
            \App\Models\TransaksiPengepul::where('nasabah_id', $userId)->sum('berat_kg');
        $activeGeolokasi = Geolokasi::where('status_aktif', 'aktif')
            ->select('nama_lokasi', 'alamat', 'latitude', 'longitude', 'jam_operasional')
            ->get()
            ->map(function ($item) {
                return [
                    'nama_lokasi'     => $item->nama_lokasi,
                    'alamat'          => $item->alamat,
                    'latitude'        => $item->latitude,
                    'longitude'       => $item->longitude,
                    'jam_operasional' => $item->jam_operasional,
                    'tipe'            => 'Cabang Utama'
                ];
            });

        $activePengepul = \App\Models\Pengepul::where('status_aktif', 1)
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get()
            ->map(function ($item) {
                return [
                    'nama_lokasi'     => $item->nama,
                    'alamat'          => $item->alamat,
                    'latitude'        => $item->latitude,
                    'longitude'       => $item->longitude,
                    'jam_operasional' => '08:00 - 17:00 (Setiap Hari)',
                    'tipe'            => 'Mitra Pengepul'
                ];
            });

        $data['activeGeolokasi']     = $activeGeolokasi->concat($activePengepul);
        $data['recentSetorans']      = TransaksiSetor::with('sampah')
            ->where('id_nasabah', $userId)
            ->orderBy('setor_tanggal', 'desc')
            ->take(10)
            ->get();
        $data['recentPenarikans'] = TransaksiTarik::where('id_nasabah', $userId)
            ->orderBy('tarik_tanggal', 'desc')
            ->take(10)
            ->get();
        $data['saldoNasabah'] = $nasabah->nasabah_saldo ?? 0;
        $data['allHadiahs'] = \App\Models\Hadiah::orderBy('poin_butuh')->get();
        $data['recentPenukaranRewards'] = \App\Models\PenukaranReward::with('hadiah')
            ->where('id_nasabah', $userId)
            ->orderBy('created_at', 'desc')
            ->get();
        $data['minimalPencairan'] = 100000;
    }

    return view('dashboard', $data);
})->name('dashboard');

// ============================================================
// Admin-only routes
// ============================================================
Route::middleware(['role:admin'])->group(function () {
    Route::get('/admin/settings', function () {
        $admin = Admin::find(session('user_id'));
        return view('admin.settings', ['admin' => $admin]);
    })->name('admin.settings');

    Route::post('/admin/setor-sampah', [AuthController::class, 'setorSampah'])->name('admin.setor');
    Route::post('/admin/verifikasi-nasabah/{id}', [AuthController::class, 'verifikasiNasabah'])->name('admin.verifikasi');
    Route::post('/admin/persetujuan-tarik/{id}/{action}', [AuthController::class, 'persetujuanTarik'])->name('admin.persetujuan_tarik');
    Route::post('/admin/master-sampah/update', [AuthController::class, 'updateMasterSampah'])->name('admin.master_sampah.update');
    Route::post('/admin/master-sampah/store', [AuthController::class, 'storeMasterSampah'])->name('admin.master_sampah.store');
    Route::post('/admin/master-geolokasi/store', [AuthController::class, 'storeGeolokasi'])->name('admin.master_geolokasi.store');
    Route::post('/admin/master-geolokasi/update/{id}', [AuthController::class, 'updateGeolokasi'])->name('admin.master_geolokasi.update');
    Route::post('/admin/master-geolokasi/delete/{id}', [AuthController::class, 'deleteGeolokasi'])->name('admin.master_geolokasi.delete');

    // CRUD Pengepul oleh Admin
    Route::post('/admin/pengepul/store', [AuthController::class, 'storePengepul'])->name('admin.pengepul.store');
    Route::post('/admin/pengepul/update/{id}', [AuthController::class, 'updatePengepul'])->name('admin.pengepul.update');
    Route::post('/admin/pengepul/delete/{id}', [AuthController::class, 'deletePengepul'])->name('admin.pengepul.delete');

    // Setoran Pengepul – Admin
    Route::get('/admin/setoran-pengepul', [SetoranPengepulController::class, 'index'])->name('admin.setoran.index');
    Route::get('/admin/setoran-pengepul/{id}', [SetoranPengepulController::class, 'show'])->name('admin.setoran.show');
    Route::post('/admin/setoran-pengepul/{id}/verify', [SetoranPengepulController::class, 'verify'])->name('admin.setoran.verify');
    Route::post('/admin/setoran-pengepul/{id}/reject', [SetoranPengepulController::class, 'reject'])->name('admin.setoran.reject');

    // Pencairan Saldo Nasabah – Admin
    Route::get('/admin/pencairan', [AuthController::class, 'daftarPencairan'])->name('admin.pencairan.index');
    Route::post('/admin/pencairan/{id}/approve', [AuthController::class, 'approvePencairan'])->name('admin.pencairan.approve');
    Route::post('/admin/pencairan/{id}/reject', [AuthController::class, 'rejectPencairan'])->name('admin.pencairan.reject');

    Route::get('/admin/cetak-laporan', function (\Illuminate\Http\Request $request) {
        $bulan = $request->query('bulan', date('m'));
        $tahun = $request->query('tahun', date('Y'));

        $setorans = TransaksiSetor::with(['nasabah', 'sampah'])
            ->whereMonth('setor_tanggal', $bulan)
            ->whereYear('setor_tanggal', $tahun)
            ->get();

        $penarikans = TransaksiTarik::with(['nasabah'])
            ->whereMonth('tarik_tanggal', $bulan)
            ->whereYear('tarik_tanggal', $tahun)
            ->where('status', 'disetujui')
            ->get();

        return view('admin.laporan_cetak', [
            'bulan'      => $bulan,
            'tahun'      => $tahun,
            'setorans'   => $setorans,
            'penarikans' => $penarikans,
        ]);
    })->name('admin.cetak_laporan');

    // Eco Rewards – Admin
    Route::get('/admin/penukaran-hadiah', [\App\Http\Controllers\RewardController::class, 'adminIndex'])->name('admin.penukaran.index');
    Route::post('/admin/penukaran-hadiah/{id}/approve', [\App\Http\Controllers\RewardController::class, 'adminApprove'])->name('admin.penukaran.approve');
    Route::post('/admin/penukaran-hadiah/{id}/reject', [\App\Http\Controllers\RewardController::class, 'adminReject'])->name('admin.penukaran.reject');

    // CRUD Hadiah – Admin
    Route::post('/admin/hadiah/store', [\App\Http\Controllers\RewardController::class, 'storeHadiah'])->name('admin.hadiah.store');
    Route::post('/admin/hadiah/update/{id}', [\App\Http\Controllers\RewardController::class, 'updateHadiah'])->name('admin.hadiah.update');
    Route::post('/admin/hadiah/delete/{id}', [\App\Http\Controllers\RewardController::class, 'deleteHadiah'])->name('admin.hadiah.delete');
});

// ============================================================
// Nasabah-only routes
// ============================================================
Route::middleware(['role:nasabah'])->group(function () {
    Route::get('/nasabah/tabungan', function () {
        $nasabah = Nasabah::with(['tabungan', 'gamifikasi'])->find(session('user_id'));
        return view('nasabah.tabungan', ['nasabah' => $nasabah]);
    })->name('nasabah.tabungan');

    Route::post('/nasabah/tarik-saldo', [AuthController::class, 'tarikSaldo'])->name('nasabah.tarik_saldo');

    // Pencairan saldo via pengepul
    Route::post('/nasabah/pencairan', [AuthController::class, 'ajukanPencairan'])->name('nasabah.pencairan.store');

    // Eco Rewards - Nasabah
    Route::post('/nasabah/tukar-poin', [\App\Http\Controllers\RewardController::class, 'tukarPoin'])->name('nasabah.tukar_poin');
});

// ============================================================
// Pengepul-only routes
// ============================================================
Route::middleware(['role:pengepul'])->group(function () {
    Route::get('/pengepul/dashboard', [PengepulController::class, 'dashboard'])
        ->name('pengepul.dashboard');

    Route::post('/pengepul/setor', [PengepulController::class, 'storeSetor'])
        ->name('pengepul.setor');

    Route::post('/pengepul/setoran-admin', [PengepulController::class, 'storeSetoran'])
        ->name('pengepul.setoran.store');

    Route::get('/pengepul/laporan', [PengepulController::class, 'laporan'])
        ->name('pengepul.laporan');

    Route::post('/pengepul/update-lokasi', [PengepulController::class, 'updateLokasi'])
        ->name('pengepul.update_lokasi');
});
