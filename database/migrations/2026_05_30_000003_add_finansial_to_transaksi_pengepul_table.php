<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transaksi_pengepul', function (Blueprint $table) {
            // Harga beli per kg (dari tabel sampah.sampah_harga_kg saat transaksi)
            $table->decimal('harga_beli_kg', 10, 2)->default(0)->after('berat_kg');
            // Harga pasar per kg (dari tabel sampah.sampah_harga_pasar saat transaksi)
            $table->decimal('harga_pasar_kg', 10, 2)->default(0)->after('harga_beli_kg');
            // Selisih total = (harga_pasar_kg - harga_beli_kg) * berat_kg
            $table->decimal('selisih_total', 12, 2)->default(0)->after('harga_pasar_kg');
            // Komisi pengepul = komisi_persen% dari selisih_total
            $table->decimal('komisi_pengepul', 12, 2)->default(0)->after('selisih_total');
            // Bagian admin = (100 - komisi_persen)% dari selisih_total
            $table->decimal('bagian_admin', 12, 2)->default(0)->after('komisi_pengepul');
            // Apakah sudah masuk setoran ke admin
            $table->boolean('sudah_disetor')->default(false)->after('bagian_admin');
        });
    }

    public function down(): void
    {
        Schema::table('transaksi_pengepul', function (Blueprint $table) {
            $table->dropColumn([
                'harga_beli_kg',
                'harga_pasar_kg',
                'selisih_total',
                'komisi_pengepul',
                'bagian_admin',
                'sudah_disetor',
            ]);
        });
    }
};
