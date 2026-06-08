<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Table: transaksi_pengepul (change transaksi_pengepul_foto -> transaksi_pengepul_gambar)
        if (Schema::hasColumn('transaksi_pengepul', 'transaksi_pengepul_foto')) {
            DB::statement('ALTER TABLE transaksi_pengepul CHANGE `transaksi_pengepul_foto` `transaksi_pengepul_gambar` VARCHAR(255) NULL');
        }

        // 2. Table: transaksi_setor (change foto_dokumentasi -> transaksi_setor_gambar)
        if (Schema::hasColumn('transaksi_setor', 'foto_dokumentasi')) {
            DB::statement('ALTER TABLE transaksi_setor CHANGE `foto_dokumentasi` `transaksi_setor_gambar` VARCHAR(255) NULL');
        }

        // 3. Table: transaksi_tarik (add transaksi_tarik_gambar)
        if (!Schema::hasColumn('transaksi_tarik', 'transaksi_tarik_gambar')) {
            Schema::table('transaksi_tarik', function (Blueprint $table) {
                $table->string('transaksi_tarik_gambar', 255)->nullable()->after('transaksi_tarik_catatan');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverse operations
        if (Schema::hasColumn('transaksi_tarik', 'transaksi_tarik_gambar')) {
            Schema::table('transaksi_tarik', function (Blueprint $table) {
                $table->dropColumn('transaksi_tarik_gambar');
            });
        }

        if (Schema::hasColumn('transaksi_setor', 'transaksi_setor_gambar')) {
            DB::statement('ALTER TABLE transaksi_setor CHANGE `transaksi_setor_gambar` `foto_dokumentasi` VARCHAR(255) NULL');
        }

        if (Schema::hasColumn('transaksi_pengepul', 'transaksi_pengepul_gambar')) {
            DB::statement('ALTER TABLE transaksi_pengepul CHANGE `transaksi_pengepul_gambar` `transaksi_pengepul_foto` VARCHAR(255) NULL');
        }
    }
};
