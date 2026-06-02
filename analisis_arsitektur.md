# Analisis Arsitektur Sistem Bank Sampah Realive

Berdasarkan struktur *source code* dan pola kerangka kerja (*framework*) yang diimplementasikan pada aplikasi Bank Sampah Realive, arsitektur sistem secara keseluruhan dapat didefinisikan ke dalam tiga konsep utama: **MVC (Model-View-Controller)**, **Arsitektur Monolitik (Monolithic)**, dan **Client-Server Architecture**.

---

## 1. Arsitektur Utama: MVC (Model-View-Controller)
Website ini dibangun menggunakan kerangka kerja (framework) **Laravel**, yang secara bawaan (*native*) menerapkan pola arsitektur perangkat lunak MVC.

*   **Model (`app/Models/`):** Bertugas mengelola aturan bisnis, logika data, dan interaksi langsung ke basis data (*database*). Semua tabel pada aplikasi ini (seperti `Nasabah`, `Admin`, `Tabungan`, `SetoranPengepul`) dipetakan menjadi objek Eloquent Model.
*   **View (`resources/views/`):** Bertanggung jawab atas antarmuka pengguna (UI). Sistem menggunakan *templating engine* **Blade** (`.blade.php`) untuk me-render HTML secara dinamis. Kode CSS, JS untuk fitur geolokasi, dan elemen desain visual (*Design System*) ditempatkan langsung di lapisan ini.
*   **Controller (`app/Http/Controllers/`):** Bertindak sebagai penghubung dan otak di tengah. Controller (seperti `PengepulController` dan `AuthController`) menerima *request* HTTP dari jalur (`routes/web.php`), meminta data ke Model, memproses kalkulasi bisnis (seperti perhitungan poin dan komisi), dan meneruskannya ke View yang tepat untuk ditampilkan.

### Alasan Pemilihan MVC:
*   **Pemisahan Tugas (Separation of Concerns):** Pemisahan data, logika, dan presentasi membuat kode tidak saling tumpang tindih. Pengembang antarmuka (UI/UX) dapat mengubah elemen pada berkas `.blade.php` tanpa takut merusak *logic* *backend* di *Controller* atau keamanan *database* di *Model*.
*   **Maintenance & Standarisasi:** Karena mengikuti pola yang sangat terstandarisasi, pengembang baru (atau tim) dapat dengan mudah memahami alur datanya dibandingkan kode prosedural lama (PHP native).

---

## 2. Arsitektur Deployment: Monolithic (Monolit)
Aplikasi ini memiliki arsitektur penyebaran **Monolitik**, bukan *Microservices*. Ini berarti seluruh fitur (panel Admin, dasbor Nasabah, operasional Pengepul, fitur otentikasi, dan sistem gamifikasi) digabungkan menjadi **satu kesatuan kode (*codebase*) yang tunggal**.

### Alasan Pemilihan Monolitik:
*   **Skala & Kompleksitas Saat Ini:** Aplikasi ini (Bank Sampah Digital) memiliki lingkup fitur yang erat terkait satu sama lain. Menggunakan *Microservices* (misal memisahkan server layanan GPS dengan layanan Pengepul) akan menambah kompleksitas yang sama sekali tidak perlu (seperti *network latency* dan biaya server terpisah). Arsitektur monolit jauh lebih cepat dan sederhana untuk dikembangkan pada fase ini.
*   **Kemudahan Pengujian & Deployment:** Cukup menjalankan satu mesin (contoh `php artisan serve`), seluruh ekosistem (Admin, Nasabah, Pengepul) dapat berjalan secara bersamaan karena mereka berbagi *database* yang sama.

---

## 3. Komunikasi Jaringan: Client-Server Architecture
Model perpesanannya menerapkan konsep Server Terpusat (*Client-Server*).

*   **Server (Backend):** Mesin web (Apache/Nginx/Artisan) yang memegang basis data (MySQL) dan *logic* Laravel.
*   **Client (Frontend):** Peramban web (*Browser* pengguna) baik di komputer admin maupun di gawai seluler milik pengepul/nasabah.

### Alasan Pemilihan Client-Server Berbasis Web (Server-Side Rendering/SSR):
*   Sistem aplikasi menggunakan metode SSR di mana file Blade langsung "diolah" menjadi HTML mentah di sisi Server sebelum dikirim ke Client. Ini membuat memori di ponsel nasabah/pengepul menjadi sangat ringan.
*   Data bank sampah bersifat sensitif (menyangkut nilai uang rupiah, saldo tabungan, komisi, margin laba). Seluruh perhitungan finansial dilakukan secara ketat di sisi **Server** (di dalam Controller, bukan Javascript di Client), sehingga meminimalisir kemungkinan peretasan saldo (manipulasi harga) oleh klien yang usil.

---

## Kesimpulan
Pendekatan **MVC Monolitik Berbasis Laravel (Server-Side Rendering)** adalah arsitektur yang **paling tepat dan pragmatis** untuk aplikasi Bank Sampah ini. Ia memberikan keseimbangan yang optimal antara kecepatan pengembangan (karena seluruh modul menyatu), keamanan data finansial yang kuat (kalkulasi bisnis terpusat), serta struktur kode berlapis yang sangat rapi dan mudah untuk dipelihara di kemudian hari.
