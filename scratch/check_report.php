<?php

// Bootstrap Laravel
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\TransaksiSetor;
use App\Models\TransaksiTarik;
use App\Models\TransaksiPengepul;
use App\Models\Nasabah;
use App\Models\Sampah;
use App\Models\Admin;

$nasabah = Nasabah::first();
$sampah = Sampah::first();
$admin = Admin::first();

if (!$nasabah || !$sampah || !$admin) {
    echo "Error: Seed database first (php artisan db:seed)\n";
    exit(1);
}

$bulan = date('m');
$tahun = date('Y');

// Insert a fake TransaksiSetor
TransaksiSetor::create([
    'setor_tanggal'     => now()->toDateString(),
    'setor_berat_kg'    => 15.5,
    'setor_harga_total' => 45000,
    'setor_keterangan'  => 'Test Setor Direct',
    'id_nasabah'        => $nasabah->id_nasabah,
    'id_admin'          => $admin->id_admin,
    'id_sampah'         => $sampah->id_sampah,
]);

// Insert a fake TransaksiTarik
TransaksiTarik::create([
    'transaksi_tarik_tanggal'        => now()->toDateString(),
    'transaksi_tarik_jumlah'         => 50000,
    'transaksi_tarik_bank_tujuan'    => 'BCA',
    'transaksi_tarik_nomor_rekening' => '12345678',
    'transaksi_tarik_atas_nama'      => $nasabah->nasabah_nama,
    'transaksi_tarik_sisa_saldo'     => 450000,
    'transaksi_tarik_status'         => 'disetujui',
    'id_nasabah'                     => $nasabah->id_nasabah,
    'id_admin'                       => $admin->id_admin,
]);

// Insert a fake TransaksiPengepul
TransaksiPengepul::create([
    'pengepul_id'                        => 1,
    'nasabah_id'                         => $nasabah->id_nasabah,
    'id_sampah'                          => $sampah->id_sampah,
    'berat_kg'                           => 20.0,
    'harga_beli_kg'                      => 3000,
    'harga_pasar_kg'                     => 4000,
    'nilai_idr'                          => 60000,
    'selisih_total'                      => 20000,
    'transaksi_pengepul_komisi_pengepul' => 10000,
    'bagian_admin'                       => 10000,
    'sudah_disetor'                      => true,
    'transaksi_pengepul_tanggal'         => now()->toDateString(),
]);

echo "=== INSERTED TEST RECORDS ===\n\n";

$setorans = TransaksiSetor::with(['nasabah', 'sampah'])
    ->whereMonth('setor_tanggal', $bulan)
    ->whereYear('setor_tanggal', $tahun)
    ->get();

echo "TransaksiSetor (Direct Admin): " . $setorans->count() . " records\n";
foreach ($setorans as $s) {
    echo " - Tanggal: {$s->setor_tanggal}, Nasabah: {$s->nasabah->nasabah_nama}, Sampah: {$s->sampah->sampah_nama}, Berat: {$s->setor_berat_kg} kg, Rp: {$s->setor_harga_total}\n";
}

$penarikans = TransaksiTarik::with(['nasabah'])
    ->whereMonth('transaksi_tarik_tanggal', $bulan)
    ->whereYear('transaksi_tarik_tanggal', $tahun)
    ->where('transaksi_tarik_status', 'disetujui')
    ->get();

echo "\nTransaksiTarik (Withdrawals): " . $penarikans->count() . " records\n";
foreach ($penarikans as $p) {
    echo " - Tanggal: {$p->transaksi_tarik_tanggal}, Nasabah: {$p->nasabah->nasabah_nama}, Rp: {$p->transaksi_tarik_jumlah}, Status: {$p->transaksi_tarik_status}\n";
}
