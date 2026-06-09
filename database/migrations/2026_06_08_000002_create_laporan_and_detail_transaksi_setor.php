<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ============================================================
        // Tabel detail_transaksi_setor (associative M:N antara
        // transaksi_setor dan sampah)
        // ============================================================
        Schema::create('detail_transaksi_setor', function (Blueprint $table) {
            $table->integer('id_detail')->autoIncrement();

            $table->integer('id_setor');
            $table->foreign('id_setor')->references('id_setor')->on('transaksi_setor')->onDelete('cascade');

            $table->integer('id_sampah');
            $table->foreign('id_sampah')->references('id_sampah')->on('sampah')->onDelete('cascade');

            $table->decimal('berat_kg', 10, 2);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_transaksi_setor');
    }
};

