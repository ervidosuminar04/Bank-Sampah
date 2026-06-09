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
                'pengepul_nama'        => 'Budi Pengepul',
                'pengepul_alamat'      => 'Jl. Merdeka No. 12, Kel. Sukamaju, Kec. Cibeunying',
                'pengepul_telepon'     => '081234567890',
                'pengepul_username'    => 'pengepul1',
                'pengepul_password'    => Hash::make('pengepul123'),
                'pengepul_latitude'    => -6.914744,
                'pengepul_longitude'   => 107.609810,
                'pengepul_status_aktif'=> 'aktif',
            ],
            [
                'pengepul_nama'        => 'Sari Wulandari',
                'pengepul_alamat'      => 'Jl. Pahlawan No. 5, Kel. Babakan, Kec. Babakan Ciparay',
                'pengepul_telepon'     => '082198765432',
                'pengepul_username'    => 'pengepul2',
                'pengepul_password'    => Hash::make('pengepul123'),
                'pengepul_latitude'    => -6.930500,
                'pengepul_longitude'   => 107.601200,
                'pengepul_status_aktif'=> 'aktif',
            ],
        ];

        foreach ($dataPengepul as $data) {
            Pengepul::create($data);
        }

        $this->command->info('✅ Seeder Pengepul berhasil: ' . count($dataPengepul) . ' data ditambahkan.');
    }
}
