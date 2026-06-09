<?php
// bootstrap laravel
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Pengepul;
use App\Models\Nasabah;
use App\Models\Sampah;
use App\Models\TransaksiPengepul;
use App\Models\SetoranPengepul;
use App\Models\Tabungan;
use App\Models\Gamifikasi;

// 1. Ambil atau Buat Pengepul
$pengepul = Pengepul::first();
if (!$pengepul) {
    echo "Gagal: Pengepul tidak ditemukan. Silakan jalankan seeder terlebih dahulu.\n";
    exit(1);
}

// 2. Ambil atau Buat Nasabah
$nasabah = Nasabah::first();
if (!$nasabah) {
    echo "Gagal: Nasabah tidak ditemukan. Silakan jalankan seeder terlebih dahulu.\n";
    exit(1);
}

// 3. Ambil Sampah
$sampahPlastik = Sampah::where('sampah_nama', 'like', '%Plastik%')->first() ?? Sampah::first();
$sampahKertas = Sampah::where('sampah_nama', 'like', '%Kertas%')->first() ?? Sampah::first();

if (!$sampahPlastik || !$sampahKertas) {
    echo "Gagal: Sampah tidak ditemukan. Silakan jalankan seeder terlebih dahulu.\n";
    exit(1);
}

// Hapus setoran pengepul "menunggu" lama milik pengepul ini untuk kebersihan testing
$pendingSetorans = SetoranPengepul::where('pengepul_id', $pengepul->id_pengepul)
    ->where('setoran_pengepul_status', 'menunggu')
    ->get();

foreach ($pendingSetorans as $ps) {
    $txIds = json_decode($ps->transaksi_ids, true) ?: [];
    TransaksiPengepul::whereIn('id_transaksi_pengepul', $txIds)->delete();
    $ps->delete();
}

// 4. Buat TransaksiPengepul Baru
// Transaksi 1: Plastik
$berat1 = 10.0;
$hargaBeli1 = $sampahPlastik->sampah_harga_kg;
$hargaPasar1 = $sampahPlastik->sampah_harga_pasar ?? $hargaBeli1;
$nilaiIdr1 = round($hargaBeli1 * $berat1, 2);
$selisih1 = round(($hargaPasar1 - $hargaBeli1) * $berat1, 2);
$komisi1 = round($selisih1 * 0.5, 2);
$admin1 = round($selisih1 - $komisi1, 2);

$tx1 = TransaksiPengepul::create([
    'pengepul_id' => $pengepul->id_pengepul,
    'nasabah_id' => $nasabah->id_nasabah,
    'id_sampah' => $sampahPlastik->id_sampah,
    'berat_kg' => $berat1,
    'harga_beli_kg' => $hargaBeli1,
    'harga_pasar_kg' => $hargaPasar1,
    'nilai_idr' => $nilaiIdr1,
    'selisih_total' => $selisih1,
    'transaksi_pengepul_komisi_pengepul' => $komisi1,
    'bagian_admin' => $admin1,
    'sudah_disetor' => true,
    'transaksi_pengepul_tanggal' => now()->toDateString(),
    'transaksi_pengepul_keterangan' => 'Dummy Plastik 10kg',
]);

// Transaksi 2: Kertas
$berat2 = 15.0;
$hargaBeli2 = $sampahKertas->sampah_harga_kg;
$hargaPasar2 = $sampahKertas->sampah_harga_pasar ?? $hargaBeli2;
$nilaiIdr2 = round($hargaBeli2 * $berat2, 2);
$selisih2 = round(($hargaPasar2 - $hargaBeli2) * $berat2, 2);
$komisi2 = round($selisih2 * 0.5, 2);
$admin2 = round($selisih2 - $komisi2, 2);

$tx2 = TransaksiPengepul::create([
    'pengepul_id' => $pengepul->id_pengepul,
    'nasabah_id' => $nasabah->id_nasabah,
    'id_sampah' => $sampahKertas->id_sampah,
    'berat_kg' => $berat2,
    'harga_beli_kg' => $hargaBeli2,
    'harga_pasar_kg' => $hargaPasar2,
    'nilai_idr' => $nilaiIdr2,
    'selisih_total' => $selisih2,
    'transaksi_pengepul_komisi_pengepul' => $komisi2,
    'bagian_admin' => $admin2,
    'sudah_disetor' => true,
    'transaksi_pengepul_tanggal' => now()->toDateString(),
    'transaksi_pengepul_keterangan' => 'Dummy Kertas 15kg',
]);

// 5. Buat SetoranPengepul Baru
$totalNilaiNasabah = $nilaiIdr1 + $nilaiIdr2;
$totalSelisih = $selisih1 + $selisih2;
$totalKomisiPengepul = $komisi1 + $komisi2;
$totalBagianAdmin = $admin1 + $admin2;
$totalDisetor = $totalNilaiNasabah + $totalBagianAdmin;

$setoran = SetoranPengepul::create([
    'pengepul_id' => $pengepul->id_pengepul,
    'total_nilai_nasabah' => $totalNilaiNasabah,
    'total_selisih' => $totalSelisih,
    'total_komisi_pengepul' => $totalKomisiPengepul,
    'total_bagian_admin' => $totalBagianAdmin,
    'total_disetor' => $totalDisetor,
    'transaksi_ids' => json_encode([$tx1->id_transaksi_pengepul, $tx2->id_transaksi_pengepul]),
    'setoran_pengepul_status' => 'menunggu',
    'setoran_pengepul_gambar' => 'dummy.jpg',
]);

// 6. Sesuaikan saldo nasabah & tabungan & gamifikasi
$nasabah->nasabah_saldo += $totalNilaiNasabah;
$nasabah->save();

$tabungan = Tabungan::where('id_nasabah', $nasabah->id_nasabah)->first();
if ($tabungan) {
    $tabungan->tabungan_total_setor += $totalNilaiNasabah;
    $tabungan->tabungan_saldo_akhir += $totalNilaiNasabah;
    $tabungan->tabungan_tgl_update = now();
    $tabungan->save();
}

$poinBaru = round(($berat1 + $berat2) * 10);
$gamifikasi = Gamifikasi::where('id_nasabah', $nasabah->id_nasabah)->first();
if ($gamifikasi) {
    $gamifikasi->poin_diperoleh += $poinBaru;
    $gamifikasi->total_poin += $poinBaru;
    $gamifikasi->tanggal_update = now();
    $gamifikasi->save();
}

echo "✅ Berhasil membuat dummy data Setoran Pengepul (Menunggu Verifikasi)!\n";
echo "Pengepul: {$pengepul->pengepul_nama} (Username: {$pengepul->pengepul_username})\n";
echo "Nasabah: {$nasabah->nasabah_nama} (Username: {$nasabah->nasabah_username})\n";
echo "ID Setoran Pengepul: {$setoran->id_setoran_pengepul}\n";
echo "Total Transaksi: 2 unit ({$sampahPlastik->sampah_nama} {$berat1}kg & {$sampahKertas->sampah_nama} {$berat2}kg)\n";
echo "Status Setoran: MENUNGGU VERIFIKASI (Bisa diedit oleh Admin)\n";
