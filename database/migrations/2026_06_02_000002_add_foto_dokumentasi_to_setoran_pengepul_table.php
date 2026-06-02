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
        Schema::table('setoran_pengepul', function (Blueprint $table) {
            // Foto dokumentasi untuk verifikasi setoran ke admin
            $table->string('foto_dokumentasi')->nullable()->after('catatan')
                  ->comment('Path foto dokumentasi setoran pengepul ke admin untuk verifikasi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('setoran_pengepul', function (Blueprint $table) {
            $table->dropColumn('foto_dokumentasi');
        });
    }
};
