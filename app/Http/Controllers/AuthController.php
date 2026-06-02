<?php

namespace App\Http\Controllers;

use App\Models\Nasabah;
use App\Models\Admin;
use App\Models\Pengepul;
use App\Models\Tabungan;
use App\Models\Gamifikasi;
use App\Models\TransaksiSetor;
use App\Models\TransaksiTarik;
use App\Models\Sampah;
use App\Models\Geolokasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    // Show login form
    public function showLogin()
    {
        return view('auth.login');
    }

    // Process login for both admin and nasabah
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required',
        ]);

        // Try admin first
        $admin = Admin::where('admin_username', $credentials['username'])->first();
        if ($admin && Hash::check($credentials['password'], $admin->admin_password)) {
            Session::put('user_id', $admin->id_admin);
            Session::put('user_type', 'admin');
            return redirect()->intended('/dashboard');
        }

        // Then nasabah
        $nasabah = Nasabah::where('nasabah_username', $credentials['username'])->first();
        if ($nasabah && Hash::check($credentials['password'], $nasabah->nasabah_password)) {
            Session::put('user_id', $nasabah->id_nasabah);
            Session::put('user_type', 'nasabah');
            return redirect()->intended('/dashboard');
        }

        // Then pengepul (only allow active)
        $pengepul = Pengepul::where('username', $credentials['username'])->first();
        if ($pengepul && Hash::check($credentials['password'], $pengepul->password)) {
            if (!$pengepul->status_aktif) {
                return back()->withErrors(['username' => 'Akun pengepul Anda sedang menunggu verifikasi oleh admin. Silakan tunggu hingga akun diaktifkan.'])->withInput();
            }
            Session::put('user_id', $pengepul->id);
            Session::put('user_type', 'pengepul');
            return redirect()->intended('/pengepul/dashboard');
        }

        return back()->withErrors(['username' => 'Username atau password salah.'])->withInput();
    }

    // Show registration form for nasabah (admin registration not required)
    public function showRegister()
    {
        return view('auth.register');
    }

    // Process nasabah registration
    public function register(Request $request)
    {
        $data = $request->validate([
            'nasabah_nama'     => 'required|string|max:100',
            'nasabah_nik'      => 'required|string|max:20|unique:nasabah,nasabah_nik',
            'nasabah_alamat'   => 'required|string',
            'nasabah_telepon'  => 'required|string|max:20',
            'nasabah_email'    => 'required|email|unique:nasabah,nasabah_email',
            'nasabah_username' => 'required|string|max:50|unique:nasabah,nasabah_username',
            'nasabah_password' => 'required|string|min:6|confirmed',
        ]);

        $nasabah = new Nasabah();
        $nasabah->nasabah_nama     = $data['nasabah_nama'];
        $nasabah->nasabah_nik      = $data['nasabah_nik'];
        $nasabah->nasabah_alamat   = $data['nasabah_alamat'];
        $nasabah->nasabah_telepon  = $data['nasabah_telepon'];
        $nasabah->nasabah_email    = $data['nasabah_email'];
        $nasabah->nasabah_username = $data['nasabah_username'];
        $nasabah->nasabah_password = Hash::make($data['nasabah_password']);
        $nasabah->nasabah_saldo    = 0;
        $nasabah->nasabah_tgl_daftar = now();
        $nasabah->save();

        // Generate nomor rekening tabungan unik secara otomatis
        $noRekening = 'BS-' . date('Ymd') . '-' . str_pad($nasabah->id_nasabah, 4, '0', STR_PAD_LEFT);

        // Buat rekening Tabungan default
        Tabungan::create([
            'tabungan_no_rekening' => $noRekening,
            'tabungan_total_setor' => 0,
            'tabungan_total_tarik' => 0,
            'tabungan_saldo_akhir' => 0,
            'tabungan_tgl_update'  => now(),
            'id_nasabah'           => $nasabah->id_nasabah,
        ]);

        // Buat profil Gamifikasi default
        Gamifikasi::create([
            'poin_diperoleh' => 0,
            'total_poin'     => 0,
            'level_nasabah'  => 'Bronze',
            'badge'          => 'Eco Starter',
            'tanggal_update' => now(),
            'id_nasabah'     => $nasabah->id_nasabah,
        ]);

        // Auto login after register
        Session::put('user_id', $nasabah->id_nasabah);
        Session::put('user_type', 'nasabah');
        return redirect('/dashboard');
    }

    // Process pengepul registration (public self-registration)
    public function registerPengepul(Request $request)
    {
        $data = $request->validate([
            'nama'       => 'required|string|max:100',
            'alamat'     => 'required|string',
            'telepon'    => 'required|string|max:20',
            'username'   => 'required|string|max:50|unique:pengepul,username',
            'password'   => 'required|string|min:6|confirmed',
            'gmaps_link' => 'nullable|string'
        ]);

        $latitude = null;
        $longitude = null;
        
        if (!empty($data['gmaps_link'])) {
            $url = $data['gmaps_link'];
            // Coba resolve redirect jika menggunakan short link Google Maps
            if (strpos($url, 'maps.app.goo.gl') !== false || strpos($url, 'goo.gl/maps') !== false) {
                $headers = @get_headers($url, 1);
                if ($headers && isset($headers['Location'])) {
                    $url = is_array($headers['Location']) ? end($headers['Location']) : $headers['Location'];
                }
            }
            
            // Ekstrak latitude & longitude dari URL menggunakan Regex
            if (preg_match('/@(-?\d+\.\d+),(-?\d+\.\d+)/', $url, $matches)) {
                $latitude = $matches[1];
                $longitude = $matches[2];
            } elseif (preg_match('/q=(-?\d+\.\d+),(-?\d+\.\d+)/', $url, $matches)) {
                $latitude = $matches[1];
                $longitude = $matches[2];
            } elseif (preg_match('/!3d(-?\d+\.\d+)!4d(-?\d+\.\d+)/', $url, $matches)) {
                $latitude = $matches[1];
                $longitude = $matches[2];
            }
        }

        Pengepul::create([
            'nama'         => $data['nama'],
            'alamat'       => $data['alamat'],
            'telepon'      => $data['telepon'],
            'username'     => $data['username'],
            'password'     => Hash::make($data['password']),
            'status_aktif' => false,
            'latitude'     => $latitude,
            'longitude'    => $longitude,
        ]);

        return redirect('/login')->with('success', 'Pendaftaran pengepul berhasil! Akun Anda sedang menunggu verifikasi oleh admin. Silakan login setelah diverifikasi.');
    }

    // Admin rejects (deletes) a pending pengepul registration
    public function rejectPengepul($id)
    {
        $pengepul = Pengepul::findOrFail($id);
        $nama = $pengepul->nama;
        $pengepul->delete();
        return back()->with('success', 'Pendaftaran pengepul "' . $nama . '" telah ditolak.');
    }

    // Logout user
    public function logout()
    {
        Session::flush();
        return redirect('/login');
    }

    // 1. Process trash deposit from nasabah (Penimbangan & Penyetoran)
    public function setorSampah(Request $request)
    {
        $data = $request->validate([
            'id_nasabah'     => 'required|integer|exists:nasabah,id_nasabah',
            'id_sampah'      => 'required|integer|exists:sampah,id_sampah',
            'setor_berat_kg' => 'required|numeric|min:0.01',
            'setor_keterangan' => 'nullable|string',
        ]);

        $nasabah = Nasabah::find($data['id_nasabah']);
        $sampah = Sampah::find($data['id_sampah']);

        // Hitung total rupiah
        $hargaTotal = $sampah->sampah_harga_kg * $data['setor_berat_kg'];

        // Buat transaksi setor
        TransaksiSetor::create([
            'setor_tanggal'     => now(),
            'setor_berat_kg'    => $data['setor_berat_kg'],
            'setor_harga_total' => $hargaTotal,
            'setor_keterangan'  => $data['setor_keterangan'] ?? ('Setor ' . $sampah->sampah_name . ' ' . $data['setor_berat_kg'] . ' kg'),
            'id_nasabah'        => $data['id_nasabah'],
            'id_admin'          => session('user_id'),
            'id_sampah'         => $data['id_sampah'],
        ]);

        // Update saldo di Tabungan nasabah
        $tabungan = Tabungan::where('id_nasabah', $data['id_nasabah'])->first();
        if ($tabungan) {
            $tabungan->tabungan_total_setor += $hargaTotal;
            $tabungan->tabungan_saldo_akhir += $hargaTotal;
            $tabungan->tabungan_tgl_update = now();
            $tabungan->save();
        }

        // Update saldo di Nasabah (denormalisasi)
        $nasabah->nasabah_saldo += $hargaTotal;
        $nasabah->save();

        // Update Poin Gamifikasi (1 kg = 10 Poin)
        $poinBaru = round($data['setor_berat_kg'] * 10);
        $gamifikasi = Gamifikasi::where('id_nasabah', $data['id_nasabah'])->first();
        if ($gamifikasi) {
            $gamifikasi->poin_diperoleh += $poinBaru;
            $gamifikasi->total_poin += $poinBaru;
            
            // Perbarui level & lencana berdasarkan total poin
            $totalPoin = $gamifikasi->total_poin;
            if ($totalPoin >= 500) {
                $gamifikasi->level_nasabah = 'Bintang';
                $gamifikasi->badge = 'Penyetor Bintang';
            } elseif ($totalPoin >= 100) {
                $gamifikasi->level_nasabah = 'Aktif';
                $gamifikasi->badge = 'Penyetor Konsisten';
            } else {
                $gamifikasi->level_nasabah = 'Pemula';
                $gamifikasi->badge = 'Eco Starter';
            }
            $gamifikasi->tanggal_update = now();
            $gamifikasi->save();
        }

        return back()->with('success', 'Setoran sampah berhasil dicatat! Saldo nasabah bertambah Rp ' . number_format($hargaTotal, 0, ',', '.') . ' dan mendapatkan ' . $poinBaru . ' Eco Poin.');
    }

    // 2. Request savings withdrawal from nasabah
    public function tarikSaldo(Request $request)
    {
        $data = $request->validate([
            'jumlah_tarik' => 'required|numeric|min:1000',
        ]);

        $userId = session('user_id');
        $nasabah = Nasabah::with('tabungan')->find($userId);

        if (!$nasabah || !$nasabah->tabungan) {
            return back()->withErrors(['jumlah_tarik' => 'Akun rekening tabungan tidak ditemukan.']);
        }

        if ($data['jumlah_tarik'] > $nasabah->tabungan->tabungan_saldo_akhir) {
            return back()->withErrors(['jumlah_tarik' => 'Saldo tabungan tidak mencukupi untuk melakukan penarikan sebesar ini.']);
        }

        // Buat pengajuan penarikan berstatus pending
        TransaksiTarik::create([
            'tarik_tanggal'    => now(),
            'tarik_jumlah'     => $data['jumlah_tarik'],
            'tarik_sisa_saldo' => $nasabah->tabungan->tabungan_saldo_akhir - $data['jumlah_tarik'],
            'tarik_status'     => 'pending',
            'id_nasabah'       => $userId,
            'id_admin'         => null,
        ]);

        return back()->with('success', 'Pengajuan penarikan Rp ' . number_format($data['jumlah_tarik'], 0, ',', '.') . ' berhasil dikirim dan sedang menunggu persetujuan admin.');
    }

    // 3. Approve or Reject withdrawal request (Admin)
    public function persetujuanTarik($id, $action)
    {
        $tarik = TransaksiTarik::find($id);
        if (!$tarik) {
            return back()->with('error', 'Transaksi penarikan tidak ditemukan.');
        }

        $nasabah = Nasabah::find($tarik->id_nasabah);
        $tabungan = Tabungan::where('id_nasabah', $tarik->id_nasabah)->first();

        if ($action === 'setuju') {
            if ($tarik->tarik_jumlah > $tabungan->tabungan_saldo_akhir) {
                $tarik->tarik_status = 'ditolak';
                $tarik->id_admin = session('user_id');
                $tarik->save();
                return back()->with('error', 'Penarikan ditolak otomatis karena saldo nasabah saat ini tidak mencukupi.');
            }

            // Kurangi saldo
            $tabungan->tabungan_total_tarik += $tarik->tarik_jumlah;
            $tabungan->tabungan_saldo_akhir -= $tarik->tarik_jumlah;
            $tabungan->tabungan_tgl_update = now();
            $tabungan->save();

            $nasabah->nasabah_saldo -= $tarik->tarik_jumlah;
            $nasabah->save();

            $tarik->tarik_sisa_saldo = $tabungan->tabungan_saldo_akhir;
            $tarik->tarik_status = 'disetujui';
        } else {
            $tarik->tarik_status = 'ditolak';
        }

        $tarik->id_admin = session('user_id');
        $tarik->save();

        return back()->with('success', 'Pengajuan penarikan nasabah berhasil ' . ($action === 'setuju' ? 'disetujui' : 'ditolak') . '.');
    }

    // 4. Verify new registered nasabah (Admin)
    public function verifikasiNasabah($id)
    {
        $nasabah = Nasabah::find($id);
        if (!$nasabah) {
            return back()->with('error', 'Nasabah tidak ditemukan.');
        }

        $nasabah->nasabah_status = 'aktif';
        $nasabah->save();

        return back()->with('success', 'Nasabah ' . $nasabah->nasabah_nama . ' berhasil diverifikasi dan sekarang aktif!');
    }

    // 5. Update trash price (Admin Master Sampah)
    public function updateMasterSampah(Request $request)
    {
        $data = $request->validate([
            'id_sampah'       => 'required|integer|exists:sampah,id_sampah',
            'sampah_harga_kg' => 'required|numeric|min:0',
        ]);

        $sampah = Sampah::find($data['id_sampah']);
        $sampah->sampah_harga_kg = $data['sampah_harga_kg'];
        $sampah->save();

        return back()->with('success', 'Harga sampah ' . $sampah->sampah_name . ' berhasil diperbarui menjadi Rp ' . number_format($data['sampah_harga_kg'], 0, ',', '.') . '/kg.');
    }

    // 5b. Store new trash type (Admin Master Sampah)
    public function storeMasterSampah(Request $request)
    {
        $data = $request->validate([
            'sampah_name'       => 'required|string|max:100|unique:sampah,sampah_name',
            'sampah_jenis'      => 'required|string|max:20',
            'sampah_satuan'     => 'required|string|max:10',
            'sampah_harga_kg'   => 'required|numeric|min:0',
            'sampah_keterangan' => 'nullable|string',
        ]);

        $sampah = Sampah::create([
            'sampah_name'       => $data['sampah_name'],
            'sampah_jenis'      => $data['sampah_jenis'],
            'sampah_satuan'     => $data['sampah_satuan'],
            'sampah_harga_kg'   => $data['sampah_harga_kg'],
            'sampah_keterangan' => $data['sampah_keterangan'] ?? null,
        ]);

        return back()->with('success', 'Sampah baru "' . $sampah->sampah_name . '" berhasil ditambahkan dengan harga Rp ' . number_format($sampah->sampah_harga_kg, 0, ',', '.') . '/kg.');
    }

    // 6. CRUD Geolokasi (Admin Master Geolokasi)
    public function storeGeolokasi(Request $request)
    {
        $data = $request->validate([
            'nama_lokasi'     => 'required|string|max:100',
            'alamat'          => 'required|string|max:255',
            'latitude'        => 'required|numeric|between:-90,90',
            'longitude'       => 'required|numeric|between:-180,180',
            'jam_operasional' => 'required|string|max:50',
            'status_aktif'    => 'required|string|in:aktif,nonaktif',
        ]);

        Geolokasi::create([
            'nama_lokasi'     => $data['nama_lokasi'],
            'alamat'          => $data['alamat'],
            'latitude'        => $data['latitude'],
            'longitude'       => $data['longitude'],
            'jam_operasional' => $data['jam_operasional'],
            'status_aktif'    => $data['status_aktif'],
            'id_admin'        => session('user_id'),
        ]);

        return back()->with('success', 'Lokasi pengepul baru berhasil ditambahkan.');
    }

    public function updateGeolokasi(Request $request, $id)
    {
        $data = $request->validate([
            'nama_lokasi'     => 'required|string|max:100',
            'alamat'          => 'required|string|max:255',
            'latitude'        => 'required|numeric|between:-90,90',
            'longitude'       => 'required|numeric|between:-180,180',
            'jam_operasional' => 'required|string|max:50',
            'status_aktif'    => 'required|string|in:aktif,nonaktif',
        ]);

        $lokasi = Geolokasi::find($id);
        if (!$lokasi) {
            return back()->with('error', 'Lokasi tidak ditemukan.');
        }

        $lokasi->update([
            'nama_lokasi'     => $data['nama_lokasi'],
            'alamat'          => $data['alamat'],
            'latitude'        => $data['latitude'],
            'longitude'       => $data['longitude'],
            'jam_operasional' => $data['jam_operasional'],
            'status_aktif'    => $data['status_aktif'],
            'id_admin'        => session('user_id'),
        ]);

        return back()->with('success', 'Lokasi pengepul berhasil diperbarui.');
    }

    public function deleteGeolokasi($id)
    {
        $lokasi = Geolokasi::find($id);
        if (!$lokasi) {
            return back()->with('error', 'Lokasi tidak ditemukan.');
        }

        $lokasi->delete();
        return back()->with('success', 'Lokasi pengepul berhasil dihapus.');
    }

    // ============================================================
    // 7. CRUD Pengepul oleh Admin
    // ============================================================

    public function storePengepul(Request $request)
    {
        $data = $request->validate([
            'nama'         => 'required|string|max:100',
            'alamat'       => 'required|string',
            'telepon'      => 'nullable|string|max:20',
            'username'     => 'required|string|max:50|unique:pengepul,username',
            'password'     => 'required|string|min:6',
            'latitude'     => 'nullable|numeric',
            'longitude'    => 'nullable|numeric',
        ]);

        \App\Models\Pengepul::create([
            'nama'         => $data['nama'],
            'alamat'       => $data['alamat'],
            'telepon'      => $data['telepon'] ?? null,
            'username'     => $data['username'],
            'password'     => Hash::make($data['password']),
            'latitude'     => $data['latitude'] ?? null,
            'longitude'    => $data['longitude'] ?? null,
            'status_aktif' => false,
        ]);

        return back()->with('success', 'Akun pengepul "' . $data['nama'] . '" berhasil ditambahkan. Username: ' . $data['username']);
    }
    public function updatePengepul(Request $request, $id)
    {
        $pengepul = \App\Models\Pengepul::findOrFail($id);

        $rules = [
            'nama'         => 'required|string|max:100',
            'alamat'       => 'required|string',
            'telepon'      => 'nullable|string|max:20',
            'username'     => 'required|string|max:50|unique:pengepul,username,' . $id,
            'password'     => 'nullable|string|min:6',
            'status_aktif' => 'required|boolean',
        ];

        if ($request->has('latitude')) {
            $rules['latitude'] = 'nullable|numeric';
        }
        if ($request->has('longitude')) {
            $rules['longitude'] = 'nullable|numeric';
        }

        $data = $request->validate($rules);

        $pengepul->nama         = $data['nama'];
        $pengepul->alamat       = $data['alamat'];
        $pengepul->telepon      = $data['telepon'] ?? null;
        $pengepul->username     = $data['username'];
        $pengepul->status_aktif = $data['status_aktif'];

        if (array_key_exists('latitude', $data)) {
            $pengepul->latitude = $data['latitude'];
        }
        if (array_key_exists('longitude', $data)) {
            $pengepul->longitude = $data['longitude'];
        }

        // Hanya update password jika diisi
        if (!empty($data['password'])) {
            $pengepul->password = Hash::make($data['password']);
        }

        $pengepul->save();
        return back()->with('success', 'Data pengepul "' . $pengepul->nama . '" berhasil diperbarui.');
    }
    public function deletePengepul($id)
    {
        $pengepul = \App\Models\Pengepul::find($id);
        if (!$pengepul) {
            return back()->with('error', 'Pengepul tidak ditemukan.');
        }

        $namaPengepul = $pengepul->nama;
        $pengepul->delete();
        return back()->with('success', 'Akun pengepul "' . $namaPengepul . '" berhasil dihapus.');
    }
    public function verifikasiPengepul($id)
    {
        $pengepul = \App\Models\Pengepul::findOrFail($id);
        $pengepul->status_aktif = true;
        $pengepul->save();
        return back()->with('success', 'Akun pengepul ' . $pengepul->nama . ' telah diverifikasi.');
    }


    // ──────────────────────────────────────────────────────────
    // PENCAIRAN SALDO NASABAH
    // ──────────────────────────────────────────────────────────

    /**
     * Nasabah mengajukan pencairan saldo (minimal Rp 100.000).
     */
    public function ajukanPencairan(Request $request)
    {
        $minimalPencairan = 100000;

        $data = $request->validate([
            'tarik_jumlah'         => 'required|numeric|min:' . $minimalPencairan,
            'tarik_bank_tujuan'    => 'required|string|max:50',
            'tarik_nomor_rekening' => 'required|string|max:50',
            'tarik_atas_nama'      => 'required|string|max:100',
        ], [
            'tarik_jumlah.min'     => 'Minimal pencairan adalah Rp ' . number_format($minimalPencairan, 0, ',', '.'),
            'tarik_bank_tujuan.required'    => 'Bank/E-Wallet tujuan transfer wajib diisi.',
            'tarik_nomor_rekening.required' => 'Nomor rekening/HP wajib diisi.',
            'tarik_atas_nama.required'      => 'Nama pemilik rekening wajib diisi.',
        ]);

        $nasabahId = session('user_id');
        $nasabah   = Nasabah::findOrFail($nasabahId);

        // Validasi saldo cukup
        if ($nasabah->nasabah_saldo < $data['tarik_jumlah']) {
            return back()->with('error', 'Saldo Anda tidak mencukupi. Saldo saat ini: Rp ' . number_format($nasabah->nasabah_saldo, 0, ',', '.'));
        }

        // Validasi saldo setelah pencairan tidak kurang dari 0
        if (($nasabah->nasabah_saldo - $data['tarik_jumlah']) < 0) {
            return back()->with('error', 'Jumlah pencairan melebihi saldo Anda.');
        }

        // Buat pengajuan pencairan (status menunggu)
        TransaksiTarik::create([
            'tarik_tanggal'        => now()->toDateString(),
            'tarik_jumlah'         => $data['tarik_jumlah'],
            'tarik_bank_tujuan'    => $data['tarik_bank_tujuan'],
            'tarik_nomor_rekening' => $data['tarik_nomor_rekening'],
            'tarik_atas_nama'      => $data['tarik_atas_nama'],
            'tarik_sisa_saldo'     => $nasabah->nasabah_saldo - $data['tarik_jumlah'],
            'status'               => 'menunggu',
            'id_nasabah'           => $nasabahId,
            'id_admin'             => null,
        ]);

        return back()->with('success', 'Pengajuan pencairan Rp ' . number_format($data['tarik_jumlah'], 0, ',', '.') . ' berhasil dikirim. Menunggu persetujuan admin.');
    }

    /**
     * Admin – tampilkan semua pengajuan pencairan nasabah.
     */
    public function daftarPencairan()
    {
        $menunggu    = TransaksiTarik::with('nasabah')->where('status', 'menunggu')->orderByDesc('tarik_tanggal')->get();
        $disetujui   = TransaksiTarik::with('nasabah')->where('status', 'disetujui')->orderByDesc('tarik_tanggal')->get();
        $ditolak     = TransaksiTarik::with('nasabah')->where('status', 'ditolak')->orderByDesc('tarik_tanggal')->get();
        $totalDicairkan = TransaksiTarik::where('status', 'disetujui')->sum('tarik_jumlah');

        return view('admin.pencairan.index', compact('menunggu', 'disetujui', 'ditolak', 'totalDicairkan'));
    }

    /**
     * Admin – setujui pencairan saldo nasabah.
     */
    public function approvePencairan(Request $request, $id)
    {
        $tarik = TransaksiTarik::with('nasabah')->findOrFail($id);

        if ($tarik->status !== 'menunggu') {
            return back()->with('error', 'Pengajuan ini sudah diproses sebelumnya.');
        }

        $nasabah = $tarik->nasabah;

        // Validasi ulang saldo nasabah
        if ($nasabah->nasabah_saldo < $tarik->tarik_jumlah) {
            return back()->with('error', 'Saldo nasabah tidak mencukupi saat ini.');
        }

        // Kurangi saldo nasabah
        $nasabah->nasabah_saldo -= $tarik->tarik_jumlah;
        $nasabah->save();

        // Update tabungan
        $tabungan = Tabungan::where('id_nasabah', $nasabah->id_nasabah)->first();
        if ($tabungan) {
            $tabungan->tabungan_total_tarik += $tarik->tarik_jumlah;
            $tabungan->tabungan_saldo_akhir  = $nasabah->nasabah_saldo;
            $tabungan->tabungan_tgl_update   = now();
            $tabungan->save();
        }

        // Update status pencairan
        $tarik->status   = 'disetujui';
        $tarik->id_admin = session('user_id');
        $tarik->catatan  = $request->input('catatan', 'Disetujui oleh admin.');
        $tarik->tarik_sisa_saldo = $nasabah->nasabah_saldo;
        $tarik->save();

        return back()->with('success', 'Pencairan Rp ' . number_format($tarik->tarik_jumlah, 0, ',', '.') . ' untuk nasabah ' . $nasabah->nasabah_nama . ' berhasil disetujui.');
    }

    /**
     * Admin – tolak pencairan saldo nasabah.
     */
    public function rejectPencairan(Request $request, $id)
    {
        $tarik = TransaksiTarik::with('nasabah')->findOrFail($id);

        if ($tarik->status !== 'menunggu') {
            return back()->with('error', 'Pengajuan ini sudah diproses sebelumnya.');
        }

        $tarik->status   = 'ditolak';
        $tarik->id_admin = session('user_id');
        $tarik->catatan  = $request->input('catatan', 'Ditolak oleh admin.');
        $tarik->save();

        return back()->with('success', 'Pengajuan pencairan nasabah ' . $tarik->nasabah->nasabah_nama . ' telah ditolak.');
    }
}
