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
        Schema::table('geolokasi', function (Blueprint $table) {
            // Ubah tipe latitude dan longitude agar presisinya pas untuk koordinat Indonesia
            $table->decimal('latitude', 11, 8)->change();
            $table->decimal('longitude', 11, 8)->change();
            
            // Ubah jam_operasional menjadi string agar bisa menampung teks jam kerja
            $table->string('jam_operasional', 50)->nullable()->change();
            
            // Ubah status_aktif menjadi string agar sinkron dengan model ('aktif'/'nonaktif')
            $table->string('status_aktif', 10)->default('aktif')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('geolokasi', function (Blueprint $table) {
            $table->decimal('latitude', 10, 8)->change();
            $table->decimal('longitude', 10, 8)->change();
            $table->timestamp('jam_operasional')->nullable()->change();
            $table->boolean('status_aktif')->change();
        });
    }
};
