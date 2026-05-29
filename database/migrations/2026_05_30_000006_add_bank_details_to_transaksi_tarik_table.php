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
        Schema::table('transaksi_tarik', function (Blueprint $table) {
            $table->string('tarik_bank_tujuan', 50)->nullable()->after('tarik_jumlah');
            $table->string('tarik_nomor_rekening', 50)->nullable()->after('tarik_bank_tujuan');
            $table->string('tarik_atas_nama', 100)->nullable()->after('tarik_nomor_rekening');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaksi_tarik', function (Blueprint $table) {
            $table->dropColumn(['tarik_bank_tujuan', 'tarik_nomor_rekening', 'tarik_atas_nama']);
        });
    }
};
