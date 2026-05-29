<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Perbaikan kardinalitas:
     * 1. NASABAH → TABUNGAN  : 1:1  → tambah UNIQUE pada id_nasabah di tabungan
     * 2. NASABAH → GAMIFIKASI: 1:1  → tambah UNIQUE pada id_nasabah di gamifikasi
     * 3. TRANSAKSI_SETOR → GAMIFIKASI: 1:N → tambah FK id_setor di gamifikasi
     */
    public function up(): void
    {
        // 1. Kardinalitas 1:1 – NASABAH → TABUNGAN
        Schema::table('tabungan', function (Blueprint $table) {
            $table->unique('id_nasabah', 'uq_tabungan_nasabah');
        });

        // 2 & 3. Kardinalitas 1:1 – NASABAH → GAMIFIKASI
        //         Kardinalitas 1:N – TRANSAKSI_SETOR → GAMIFIKASI
        Schema::table('gamifikasi', function (Blueprint $table) {
            // 1:1 dengan nasabah
            $table->unique('id_nasabah', 'uq_gamifikasi_nasabah');

            // 1:N dengan transaksi_setor (nullable agar data lama tidak error)
            $table->integer('id_setor')->nullable()->after('id_nasabah');
            $table->foreign('id_setor', 'fk_gamifikasi_setor')
                  ->references('id_setor')
                  ->on('transaksi_setor')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Rollback tabel gamifikasi
        Schema::table('gamifikasi', function (Blueprint $table) {
            $table->dropForeign('fk_gamifikasi_setor');
            $table->dropColumn('id_setor');
            $table->dropUnique('uq_gamifikasi_nasabah');
        });

        // Rollback tabel tabungan
        Schema::table('tabungan', function (Blueprint $table) {
            $table->dropUnique('uq_tabungan_nasabah');
        });
    }
};
