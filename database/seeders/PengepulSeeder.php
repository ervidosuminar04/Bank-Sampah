<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Pengepul;

class PengepulSeeder extends Seeder
{
    public function run(): void
    {
        // Nonaktifkan FK sementara agar truncate bisa berjalan
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        \App\Models\TransaksiPengepul::truncate();
        \App\Models\Pengepul::truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $dataPengepul = [
            [
                'nama'        => 'Budi Santoso',
                'alamat'      => 'Jl. Merdeka No. 12, Kel. Sukamaju, Kec. Cibeunying',
                'telepon'     => '081234567890',
                'username'    => 'pengepul1',
                'password'    => Hash::make('pengepul123'),
                'latitude'    => -6.914744,
                'longitude'   => 107.609810,
                'status_aktif' => true,
            ],
            [
                'nama'        => 'Sari Wulandari',
                'alamat'      => 'Jl. Pahlawan No. 5, Kel. Babakan, Kec. Babakan Ciparay',
                'telepon'     => '082198765432',
                'username'    => 'pengepul2',
                'password'    => Hash::make('pengepul123'),
                'latitude'    => -6.930500,
                'longitude'   => 107.601200,
                'status_aktif' => true,
            ],
        ];

        foreach ($dataPengepul as $data) {
            Pengepul::create($data);
        }

        $this->command->info('✅ Seeder Pengepul berhasil: ' . count($dataPengepul) . ' data ditambahkan.');
    }
}
