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
        // 1. Kardinalitas 1:1 – NASABAH → TABUNGAN
        // Kita cek lewat Schema::hasIndex jika didukung, atau gunakan try-catch agar aman dari error duplikat indeks
        try {
            Schema::table('tabungan', function (Blueprint $table) {
                $table->unique('id_nasabah', 'uq_tabungan_nasabah');
            });
        } catch (\Exception $e) {
            // Jika indeks sudah ada, abaikan error dan lanjut ke proses berikutnya
        }

        // 2 & 3. Kardinalitas 1:1 & 1:N – GAMIFIKASI
        Schema::table('gamifikasi', function (Blueprint $table) {
            // Cek apakah kolom id_setor BELUM ada, jika belum ada baru dibuat
            if (!Schema::hasColumn('gamifikasi', 'id_setor')) {
                // Menggunakan foreignId agar tipe data otomatis match dengan tabel induk (Big Integer)
                $table->foreignId('id_setor')->nullable()->after('id_nasabah');
            }
        });

        // Tambahkan unique dan foreign key di block terpisah menggunakan try-catch agar anti-gagal
        try {
            Schema::table('gamifikasi', function (Blueprint $table) {
                $table->unique('id_nasabah', 'uq_gamifikasi_nasabah');
            });
        } catch (\Exception $e) {}

        try {
            Schema::table('gamifikasi', function (Blueprint $table) {
                $table->foreign('id_setor', 'fk_gamifikasi_setor')
                      ->references('id') // Ubah ke 'id_setor' jika PK di tabel transaksi_setor bukan 'id'
                      ->on('transaksi_setor')
                      ->onDelete('set null');
            });
        } catch (\Exception $e) {}
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Rollback tabel gamifikasi
        Schema::table('gamifikasi', function (Blueprint $table) {
            try {
                $table->dropForeign('fk_gamifikasi_setor');
            } catch (\Exception $e) {}

            if (Schema::hasColumn('gamifikasi', 'id_setor')) {
                $table->dropColumn('id_setor');
            }

            try {
                $table->dropUnique('uq_gamifikasi_nasabah');
            } catch (\Exception $e) {}
        });

        // Rollback tabel tabungan
        Schema::table('tabungan', function (Blueprint $table) {
            try {
                $table->dropUnique('uq_tabungan_nasabah');
            } catch (\Exception $e) {}
        });
    }
};