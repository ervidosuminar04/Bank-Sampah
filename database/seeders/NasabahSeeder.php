<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Nasabah;
use App\Models\Tabungan;
use App\Models\Gamifikasi;
use Illuminate\Support\Facades\Hash;

class NasabahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cari dan bersihkan data Budi lama jika ada untuk menghindari constraint error
        $existing = Nasabah::where('nasabah_username', 'budi')->first();
        if ($existing) {
            // Hapus relasi tabungan dan gamifikasi budi terlebih dahulu
            Tabungan::where('id_nasabah', $existing->id_nasabah)->delete();
            Gamifikasi::where('id_nasabah', $existing->id_nasabah)->delete();
            $existing->delete();
        }

        // 1. Buat Nasabah Budi
        $nasabah = Nasabah::create([
            'nasabah_nama'     => 'Budi Santoso',
            'nasabah_nik'      => '3171012345678001',
            'nasabah_alamat'   => 'Jl. Merdeka No. 10, Jakarta Pusat',
            'nasabah_telepon'  => '081234567890',
            'nasabah_email'    => 'budi@example.com',
            'nasabah_username' => 'budi',
            'nasabah_password' => Hash::make('budi123'),
            'nasabah_saldo'    => 500000.00,
            'nasabah_tgl_daftar'=> now()->subMonths(2),
            'nasabah_status'   => 'aktif',
        ]);

        // 2. Buat Rekening Tabungan Budi
        Tabungan::create([
            'tabungan_no_rekening' => 'BS-' . date('Ymd') . '-' . str_pad($nasabah->id_nasabah, 4, '0', STR_PAD_LEFT),
            'tabungan_total_setor' => 600000.00,
            'tabungan_total_tarik' => 100000.00,
            'tabungan_saldo_akhir' => 500000.00,
            'tabungan_tgl_update'  => now(),
            'id_nasabah'           => $nasabah->id_nasabah,
        ]);

        // 3. Buat Profil Gamifikasi Budi
        Gamifikasi::create([
            'poin_diperoleh' => 150,
            'total_poin'     => 150,
            'level_nasabah'  => 'Bronze',
            'badge'          => 'Eco Starter',
            'tanggal_update' => now(),
            'id_nasabah'     => $nasabah->id_nasabah,
        ]);
    }
}
