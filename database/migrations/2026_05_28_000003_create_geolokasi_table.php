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
        Schema::create('geolokasi', function (Blueprint $table) {
            $table->integer('id_lokasi')->autoIncrement();
            $table->string('nama_lokasi', 100);
            $table->string('alamat', 255);
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 10, 8);
            $table->timestamp('jam_operasional')->nullable();
            $table->boolean('status_aktif');
            
            // Relasi ke tabel admin (mengelola)
            $table->integer('id_admin')->nullable();
            $table->foreign('id_admin')->references('id_admin')->on('admin')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::downIfExists('geolokasi');
    }
};
