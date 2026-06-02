# Analisis Query Database (Eloquent ORM) berdasarkan Peran (Role)

Berikut adalah hasil analisis terkait *query* (permintaan ke basis data) apa saja yang digunakan pada masing-masing peran (Admin, Nasabah, dan Pengepul) di dalam aplikasi "Bank Sampah Realive". Aplikasi ini menggunakan Laravel Eloquent ORM.

---

## 1. Role: Admin
Admin memiliki akses ke dasbor pusat dan fitur verifikasi. *Query* yang dijalankan didominasi oleh fungsi agregasi (perhitungan ringkasan) dan fungsi relasional.

### A. Dashboard Overview (`web.php`)
* **Pengguna (Nasabah & Pengepul):**
  * `Nasabah::where('nasabah_status', 'aktif')->count()` (Menghitung total nasabah aktif).
  * `Nasabah::where('nasabah_status', 'pending')->get()` (Mengambil daftar nasabah yang belum diverifikasi).
  * `Pengepul::orderBy('nama')->get()` dan `Pengepul::where('status_aktif', false)->get()` (Mengambil daftar seluruh pengepul dan yang belum diverifikasi).
* **Sampah & Geografis:**
  * `Geolokasi::count()` dan `Geolokasi::orderBy('id_lokasi', 'desc')->get()` (Mengambil data titik lokasi map).
  * `Sampah::orderBy('sampah_name', 'asc')->get()` (Mendapatkan master data sampah).
* **Finansial & Setoran:**
  * `TransaksiSetor::whereMonth(...)->sum('setor_berat_kg')` dan `TransaksiPengepul::whereMonth(...)->sum('berat_kg')` (Mengagregasi total sampah bulanan masuk).
  * `Tabungan::sum('tabungan_total_setor')` (Total perputaran uang di bank sampah/kas masuk).
  * `TransaksiTarik::where('status', 'disetujui')->sum('tarik_jumlah')` (Total uang yang ditarik nasabah).
  * `SetoranPengepul::where('status', 'terverifikasi')->sum('total_bagian_admin')` (Menghitung total margin/pendapatan bersih admin dari pengepul).

### B. Kelola Setoran Pengepul (`SetoranPengepulController.php`)
* **Daftar Setoran:**
  * `SetoranPengepul::with('pengepul')->where('status', 'menunggu')->get()` (Menarik setoran (batch) yang butuh verifikasi (dengan relasi nama pengepul)).
* **Detail Transaksi (Nested):**
  * `SetoranPengepul::findOrFail($id)` diikuti dengan pencarian data rincian transaksi:
  * `TransaksiPengepul::with(['nasabah', 'sampah'])->whereIn('id', $setoran->transaksi_ids)->get()` (Mencari semua transaksi individu pengepul-nasabah yang tergabung dalam satu paket setoran ke admin).
* **Penolakan Setoran:**
  * Jika ditolak admin, sistem mereset kembali status `sudah_disetor` dengan query bulk update:
  * `TransaksiPengepul::whereIn('id', $setoran->transaksi_ids)->update(['sudah_disetor' => false])`.

---

## 2. Role: Nasabah
*Query* yang dieksekusi bagi Nasabah difokuskan pada pengambilan relasi pengguna spesifik dan agregasi performa individu.

### A. Dashboard & Data Personal (`web.php`)
* **Load Relasi Pengguna:**
  * `Nasabah::with(['tabungan', 'gamifikasi'])->find($userId)` (Mencari data nasabah lengkap beserta relasi buku tabungannya dan data e-gamifikasinya).
  * *(Auto-Initialize)* Jika belum ada tabungan/gamifikasi (pengguna baru): `$nasabah->tabungan()->create(...)`.
* **Performa Sampah Pribadi:**
  * Menghitung semua riwayat kilogram (total dari 2 jenis transaksi — via Pengepul dan Langsung ke Bank Sampah):
    * `TransaksiSetor::where('id_nasabah', $userId)->sum('setor_berat_kg')`
    * `TransaksiPengepul::where('nasabah_id', $userId)->sum('berat_kg')`

### B. Geolokasi & History Transaksi
* **Pencarian Map/GPS Terdekat:**
  * `Geolokasi::where('status_aktif', 'aktif')->get()` (Ambil titik koordinat cabang utama bank sampah).
  * `Pengepul::where('status_aktif', 1)->whereNotNull('latitude')->whereNotNull('longitude')->get()` (Ambil koordinat GPS *real-time* milik mitra pengepul truk keliling).
* **Riwayat & Hadiah:**
  * `TransaksiSetor::with('sampah')->where('id_nasabah', $userId)->orderBy(...)->take(10)->get()` (Daftar 10 riwayat setoran teratas).
  * `TransaksiTarik::where('id_nasabah', ...)` (Riwayat penarikan dana).
  * `Hadiah::orderBy('poin_butuh')->get()` (Mengambil semua list katalog Eco Rewards).

---

## 3. Role: Pengepul
Pengepul bertindak sebagai perantara yang melakukan modifikasi pada banyak tabel (Nasabah, Tabungan, Transaksi, Setoran). *Query* pengepul paling sarat akan agregasi keuangan.

### A. Dashboard & Statistik (`PengepulController.php`)
* **Overview Pribadi:**
  * `$pengepul = Pengepul::findOrFail($pengepulId)`
  * `$pengepul->transaksi()->count()` (Total melayani transaksi).
  * `$pengepul->transaksi()->sum('nilai_idr')` (Total uang keluar untuk membayar nasabah).
  * `$pengepul->transaksi()->sum('komisi_pengepul')` (Total komisi untung yang disimpan pengepul).

### B. Input Setoran Sampah (Timbangan)
* Saat pengepul mencatat sampah dari nasabah:
  * **Insersi Transaksi:** `TransaksiPengepul::create(...)`
  * **Pembaruan Saldo Nasabah:** `Tabungan::where('id_nasabah', $nasabahId)->first()` lalu memperbarui atribut nominal uang dan `save()`.
  * **Pembaruan Poin Nasabah:** `Gamifikasi::where('id_nasabah', $nasabahId)->first()` lalu memperbarui poin (1 Kg = 10 poin) dan naik level/badge secara otomatis menggunakan update query biasa.

### C. Setoran Ke Admin (Pencairan Margin)
* Saat pengepul mengirim sampah/laporan ke Admin Pusat:
  * `TransaksiPengepul::whereIn('id', $request->transaksi_ids)->where('pengepul_id', $pengepulId)->where('sudah_disetor', false)->get()` (Memvalidasi hanya mengambil transaksi yang belum dilaporkan).
  * Setelah valid, agregasi `.sum()` berjalan, kemudian pembuatan row `SetoranPengepul::create(...)`.
  * *Update* seluruh transaksi menjadi "dilaporkan": `TransaksiPengepul::whereIn('id', ...)->update(['sudah_disetor' => true])`.

### D. Fitur GPS Terkini
* Saat pengepul berpindah lokasi:
  * Update kordinat `latitude` dan `longitude` secara langsung ke model `$pengepul` dan mengeksekusi method `save()`.

---

## Kesimpulan Implementasi Query
Keseluruhan kueri tidak ada yang menggunakan raw SQL, melainkan 100% bergantung pada **Eloquent ORM** (seperti `::find`, `::where`, `::count`, `::sum`, `->create()`, `->update()`, dan `->with()`). Pendekatan ini memudahkan pengelolaan *Eager Loading* (`with()`) untuk menghindari masalah iterasi berat/ *N+1 query problem* saat memanggil relasi nasabah ↔ transaksi ↔ sampah.
