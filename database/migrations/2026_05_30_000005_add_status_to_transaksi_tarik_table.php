<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transaksi_tarik', function (Blueprint $table) {
            // Status pengajuan pencairan saldo nasabah
            $table->enum('status', ['menunggu', 'disetujui', 'ditolak'])->default('menunggu')->after('tarik_sisa_saldo');
            $table->text('catatan')->nullable()->after('status');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::table('transaksi_tarik', function (Blueprint $table) {
            $table->dropColumn(['status', 'catatan']);
            $table->dropTimestamps();
        });
    }
};
