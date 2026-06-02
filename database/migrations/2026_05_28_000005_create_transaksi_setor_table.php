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
        Schema::create('transaksi_setor', function (Blueprint $table) {
            $table->integer('id_setor')->autoIncrement();
            $table->date('setor_tanggal');
            $table->decimal('setor_berat_kg', 10, 2);
            $table->decimal('setor_harga_total', 15, 2);
            $table->text('setor_keterangan')->nullable();
            $table->string('foto_dokumentasi')->nullable()
                  ->comment('Path foto dokumentasi transaksi setor untuk verifikasi');
            
            // Relasi ke nasabah (melakukan)
            $table->integer('id_nasabah');
            $table->foreign('id_nasabah')->references('id_nasabah')->on('nasabah')->onDelete('cascade');
            
            // Relasi ke admin (diproses_setor)
            $table->integer('id_admin')->nullable();
            $table->foreign('id_admin')->references('id_admin')->on('admin')->onDelete('set null');
            
            // Relasi ke sampah (dicatat_dalam)
            $table->integer('id_sampah');
            $table->foreign('id_sampah')->references('id_sampah')->on('sampah')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::downIfExists('transaksi_setor');
    }
};
