<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TransaksiSetor;
use App\Models\TransaksiTarik;
use App\Models\TransaksiPengepul;
use App\Models\Nasabah;
use App\Models\Sampah;
use App\Models\Admin;
use App\Models\Pengepul;
use App\Models\Tabungan;
use Carbon\Carbon;

class TransactionSeeder extends Seeder
{
    public function run(): void
    {
        // Clean old transactions
        TransaksiSetor::query()->delete();
        TransaksiTarik::query()->delete();
        TransaksiPengepul::query()->delete();

        $nasabah = Nasabah::first();
        $admin = Admin::first();
        $pengepul = Pengepul::first();

        $sampahPlastik = Sampah::where('sampah_nama', 'like', '%Plastik%')->first() ?? Sampah::first();
        $sampahKertas = Sampah::where('sampah_nama', 'like', '%Kertas%')->first() ?? Sampah::first();
        $sampahLogam = Sampah::where('sampah_nama', 'like', '%Logam%')->first() ?? Sampah::first();

        if (!$nasabah || !$admin || !$pengepul) {
            $this->command->error('Error: Seed Nasabah, Admin, and Pengepul first!');
            return;
        }

        $now = Carbon::now();

        // 1. Seed TransaksiSetor (Penyetoran Langsung via Admin)
        TransaksiSetor::create([
            'setor_tanggal'          => $now->copy()->subDays(5)->toDateString(),
            'setor_berat_kg'         => 12.5,
            'setor_harga_total'      => $sampahPlastik->sampah_harga_kg * 12.5,
            'setor_keterangan'       => 'Penyetoran botol plastik PET bersih',
            'id_nasabah'             => $nasabah->id_nasabah,
            'id_admin'               => $admin->id_admin,
            'id_sampah'              => $sampahPlastik->id_sampah,
        ]);

        TransaksiSetor::create([
            'setor_tanggal'          => $now->copy()->subDays(3)->toDateString(),
            'setor_berat_kg'         => 8.0,
            'setor_harga_total'      => $sampahKertas->sampah_harga_kg * 8.0,
            'setor_keterangan'       => 'Penyetoran tumpukan kardus bekas',
            'id_nasabah'             => $nasabah->id_nasabah,
            'id_admin'               => $admin->id_admin,
            'id_sampah'              => $sampahKertas->id_sampah,
        ]);

        // 2. Seed TransaksiPengepul (Penyetoran via Mitra Pengepul)
        TransaksiPengepul::create([
            'pengepul_id'                        => $pengepul->id_pengepul,
            'nasabah_id'                         => $nasabah->id_nasabah,
            'id_sampah'                          => $sampahLogam->id_sampah,
            'berat_kg'                           => 5.0,
            'harga_beli_kg'                      => $sampahLogam->sampah_harga_kg,
            'harga_pasar_kg'                     => $sampahLogam->sampah_harga_kg + 1000,
            'nilai_idr'                          => $sampahLogam->sampah_harga_kg * 5.0,
            'selisih_total'                      => 5000,
            'transaksi_pengepul_komisi_pengepul' => 2500,
            'bagian_admin'                       => 2500,
            'sudah_disetor'                      => true,
            'transaksi_pengepul_tanggal'         => $now->copy()->subDays(2)->toDateString(),
            'transaksi_pengepul_keterangan'      => 'Penyetoran kaleng aluminium',
        ]);

        // 3. Seed TransaksiTarik (Pencairan Saldo Nasabah)
        TransaksiTarik::create([
            'transaksi_tarik_tanggal'        => $now->copy()->subDays(4)->toDateString(),
            'transaksi_tarik_jumlah'         => 150000.00,
            'transaksi_tarik_bank_tujuan'    => 'BCA',
            'transaksi_tarik_nomor_rekening' => '8012345678',
            'transaksi_tarik_atas_nama'      => $nasabah->nasabah_nama,
            'transaksi_tarik_sisa_saldo'     => 350000.00,
            'transaksi_tarik_status'         => 'disetujui',
            'id_nasabah'                     => $nasabah->id_nasabah,
            'id_admin'                       => $admin->id_admin,
            'transaksi_tarik_catatan'        => 'Transfer disetujui dan diproses oleh admin.',
        ]);

        TransaksiTarik::create([
            'transaksi_tarik_tanggal'        => $now->copy()->subDays(1)->toDateString(),
            'transaksi_tarik_jumlah'         => 100000.00,
            'transaksi_tarik_bank_tujuan'    => 'Mandiri',
            'transaksi_tarik_nomor_rekening' => '1320098765432',
            'transaksi_tarik_atas_nama'      => $nasabah->nasabah_nama,
            'transaksi_tarik_sisa_saldo'     => 250000.00,
            'transaksi_tarik_status'         => 'disetujui',
            'id_nasabah'                     => $nasabah->id_nasabah,
            'id_admin'                       => $admin->id_admin,
            'transaksi_tarik_catatan'        => 'Transfer diproses secara instan.',
        ]);

        // Update Nasabah & Tabungan Balances based on seeded transactions
        $totalSetor = (12.5 * $sampahPlastik->sampah_harga_kg) + (8.0 * $sampahKertas->sampah_harga_kg) + (5.0 * $sampahLogam->sampah_harga_kg); // 37500 + 16000 + 25000 = 78500
        $totalTarik = 250000.00;

        $tabungan = Tabungan::where('id_nasabah', $nasabah->id_nasabah)->first();
        if ($tabungan) {
            $tabungan->tabungan_total_setor = $totalSetor;
            $tabungan->tabungan_total_tarik = $totalTarik;
            $tabungan->tabungan_saldo_akhir = $totalSetor - $totalTarik; // Note: balance can go negative in fake data but nasabah_saldo is adjusted.
            $tabungan->save();
        }

        $nasabah->nasabah_saldo = $totalSetor - $totalTarik;
        $nasabah->save();

        $this->command->info('✅ TransactionSeeder berhasil: dummy data transaksi setor & tarik untuk bulan ini ditambahkan.');
    }
}
