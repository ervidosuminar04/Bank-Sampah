<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('transaksi_pengepul', function (Blueprint $table) {
            // Foto dokumentasi untuk verifikasi transaksi timbangan
            $table->string('foto_dokumentasi')->nullable()->after('keterangan')
                  ->comment('Path foto dokumentasi timbangan sampah untuk verifikasi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaksi_pengepul', function (Blueprint $table) {
            $table->dropColumn('foto_dokumentasi');
        });
    }
};
