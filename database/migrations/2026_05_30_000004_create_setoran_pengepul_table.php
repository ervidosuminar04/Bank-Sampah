<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('setoran_pengepul', function (Blueprint $table) {
            $table->bigIncrements('id');

            // Pengepul yang melakukan setoran
            $table->unsignedBigInteger('pengepul_id');
            $table->foreign('pengepul_id')->references('id')->on('pengepul')->onDelete('cascade');

            // Rincian finansial setoran
            $table->decimal('total_nilai_nasabah', 15, 2);     // total saldo yg sudah dikreditkan ke nasabah
            $table->decimal('total_selisih', 15, 2);           // total selisih harga pasar - harga beli
            $table->decimal('total_komisi_pengepul', 15, 2);   // 50% dari selisih (komisi pengepul)
            $table->decimal('total_bagian_admin', 15, 2);      // 50% dari selisih (bagian admin)
            $table->decimal('total_disetor', 15, 2);           // nilai_nasabah + bagian_admin (yg disetor ke admin)

            // Daftar id transaksi yang termasuk dalam setoran ini
            $table->json('transaksi_ids');

            // Status verifikasi
            $table->enum('status', ['menunggu', 'terverifikasi', 'ditolak'])->default('menunggu');

            // Admin yang memproses
            $table->integer('id_admin')->nullable();
            $table->foreign('id_admin')->references('id_admin')->on('admin')->onDelete('set null');

            // Catatan tambahan
            $table->text('catatan')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('setoran_pengepul');
    }
};
