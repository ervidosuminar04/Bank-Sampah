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
        Schema::create('transaksi_tarik', function (Blueprint $table) {
            $table->integer('id_tarik')->autoIncrement();
            $table->date('tarik_tanggal');
            $table->decimal('tarik_jumlah', 15, 2);
            $table->decimal('tarik_sisa_saldo', 15, 2);
            
            // Relasi ke nasabah (mengajukan)
            $table->integer('id_nasabah');
            $table->foreign('id_nasabah')->references('id_nasabah')->on('nasabah')->onDelete('cascade');
            
            // Relasi ke admin (diproses_tarik)
            $table->integer('id_admin')->nullable();
            $table->foreign('id_admin')->references('id_admin')->on('admin')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::downIfExists('transaksi_tarik');
    }
};
