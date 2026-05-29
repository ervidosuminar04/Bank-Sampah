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
        Schema::create('tabungan', function (Blueprint $table) {
            $table->integer('id_tabungan')->autoIncrement();
            $table->string('tabungan_no_rekening', 20)->unique();
            $table->decimal('tabungan_total_setor', 15, 2);
            $table->decimal('tabungan_total_tarik', 15, 2);
            $table->decimal('tabungan_saldo_akhir', 15, 2);
            $table->date('tabungan_tgl_update');
            
            // Relasi ke nasabah (memiliki) – kardinalitas 1:1
            $table->integer('id_nasabah')->unique();
            $table->foreign('id_nasabah')->references('id_nasabah')->on('nasabah')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::downIfExists('tabungan');
    }
};
