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
        // Tambahkan status ke transaksi_tarik
        if (Schema::hasTable('transaksi_tarik')) {
            Schema::table('transaksi_tarik', function (Blueprint $table) {
                if (!Schema::hasColumn('transaksi_tarik', 'tarik_status')) {
                    $table->string('tarik_status', 20)->default('pending'); // pending, disetujui, ditolak
                }
            });
        }

        // Tambahkan status ke nasabah
        if (Schema::hasTable('nasabah')) {
            Schema::table('nasabah', function (Blueprint $table) {
                if (!Schema::hasColumn('nasabah', 'nasabah_status')) {
                    $table->string('nasabah_status', 20)->default('pending'); // pending, aktif
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('transaksi_tarik')) {
            Schema::table('transaksi_tarik', function (Blueprint $table) {
                $table->dropColumn('tarik_status');
            });
        }

        if (Schema::hasTable('nasabah')) {
            Schema::table('nasabah', function (Blueprint $table) {
                $table->dropColumn('nasabah_status');
            });
        }
    }
};
