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
        Schema::create('laporan', function (Blueprint $table) {
            $table->integer('id_laporan')->autoIncrement();
            $table->string('laporan_jenis', 50);
            $table->string('laporan_periode', 20);
            $table->decimal('laporan_total_kg', 10, 2);
            $table->decimal('laporan_total_nilai', 15, 2);
            $table->date('laporan_tgl_buat');
            
            // Relasi ke admin (membuat)
            $table->integer('id_admin')->nullable();
            $table->foreign('id_admin')->references('id_admin')->on('admin')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::downIfExists('laporan');
    }
};
