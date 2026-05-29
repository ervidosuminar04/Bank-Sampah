<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Bersihkan data admin lama untuk menghindari duplikasi saat di-seed ulang
        Admin::query()->delete();

        Admin::create([
            'admin_username' => 'admin',
            'admin_password' => Hash::make('admin123'),
            'admin_nama'     => 'Administrator',
            'admin_level'    => 'admin',
            'admin_status'   => 'aktif',
        ]);
    }
}
