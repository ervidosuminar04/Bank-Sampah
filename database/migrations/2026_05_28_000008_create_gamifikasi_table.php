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
        Schema::create('gamifikasi', function (Blueprint $table) {
            $table->integer('id_reward')->autoIncrement();
            $table->integer('poin_diperoleh');
            $table->integer('total_poin');
            $table->string('level_nasabah', 50);
            $table->string('badge', 50)->nullable();
            $table->date('tanggal_update');
            
            // Relasi ke nasabah (pemilik reward) – kardinalitas 1:1
            $table->integer('id_nasabah')->unique();
            $table->foreign('id_nasabah')->references('id_nasabah')->on('nasabah')->onDelete('cascade');

            // Relasi ke transaksi_setor – kardinalitas 1:N
            $table->integer('id_setor')->nullable();
            $table->foreign('id_setor')->references('id_setor')->on('transaksi_setor')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::downIfExists('gamifikasi');
    }
};
