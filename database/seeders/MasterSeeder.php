<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Sampah;
use App\Models\Geolokasi;
use App\Models\Admin;

class MasterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Bersihkan Data Lama
        Sampah::query()->delete();
        Geolokasi::query()->delete();

        // Ambil ID admin pertama untuk kepemilikan lokasi
        $admin = Admin::first();
        $adminId = $admin ? $admin->id_admin : null;

        // 2. Seed Data Master Sampah
        Sampah::create([
            'sampah_name'       => 'Plastik PET (Botol/Gelas)',
            'sampah_jenis'      => 'Plastik',
            'sampah_satuan'     => 'kg',
            'sampah_harga_kg'   => 3000.00,
            'sampah_keterangan' => 'Botol plastik bersih transparan bekas minuman.',
        ]);

        Sampah::create([
            'sampah_name'       => 'Kertas Karton / Kardus',
            'sampah_jenis'      => 'Kertas',
            'sampah_satuan'     => 'kg',
            'sampah_harga_kg'   => 2000.00,
            'sampah_keterangan' => 'Karton tebal kering, kertas koran, atau buku bekas.',
        ]);

        Sampah::create([
            'sampah_name'       => 'Besi / Logam Bekas',
            'sampah_jenis'      => 'Logam',
            'sampah_satuan'     => 'kg',
            'sampah_harga_kg'   => 5000.00,
            'sampah_keterangan' => 'Kaleng minuman aluminium, besi cor, tembaga, atau seng.',
        ]);

        Sampah::create([
            'sampah_name'       => 'Pecahan Kaca / Botol Kaca',
            'sampah_jenis'      => 'Kaca',
            'sampah_satuan'     => 'kg',
            'sampah_harga_kg'   => 1500.00,
            'sampah_keterangan' => 'Botol sirup kaca tebal atau pecahan kaca transparan/warna.',
        ]);

        // 3. Seed Data Master Geolokasi
        Geolokasi::create([
            'nama_lokasi'     => 'Pengepul Eco Jaya',
            'alamat'          => 'Jl. Sudirman No. 23, Jakarta Pusat',
            'latitude'        => -6.1824,
            'longitude'       => 106.8294,
            'jam_operasional' => '08:00 - 17:00',
            'status_aktif'    => 'aktif',
            'id_admin'        => $adminId,
        ]);

        Geolokasi::create([
            'nama_lokasi'     => 'Pengepul Berkah Hijau',
            'alamat'          => 'Jl. Thamrin No. 45, Jakarta Pusat',
            'latitude'        => -6.1950,
            'longitude'       => 106.8320,
            'jam_operasional' => '09:00 - 16:00',
            'status_aktif'    => 'aktif',
            'id_admin'        => $adminId,
        ]);

        Geolokasi::create([
            'nama_lokasi'     => 'Depo Bank Sampah Lestari',
            'alamat'          => 'Jl. Gajah Mada No. 12, Jakarta Barat',
            'latitude'        => -6.1685,
            'longitude'       => 106.8142,
            'jam_operasional' => '08:00 - 15:00',
            'status_aktif'    => 'nonaktif',
            'id_admin'        => $adminId,
        ]);
    }
}
