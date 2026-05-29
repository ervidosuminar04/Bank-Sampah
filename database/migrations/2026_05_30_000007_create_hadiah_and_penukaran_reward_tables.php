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
        // 1. Tabel Hadiah (Katalog Barang)
        Schema::create('hadiah', function (Blueprint $table) {
            $table->bigIncrements('id_hadiah');
            $table->string('nama_hadiah', 100);
            $table->integer('poin_butuh');
            $table->integer('stok')->default(0);
            $table->string('gambar', 255)->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });

        // 2. Tabel Penukaran Reward (Transaksi Poin)
        Schema::create('penukaran_reward', function (Blueprint $table) {
            $table->bigIncrements('id_penukaran');
            
            // Relasi ke nasabah
            $table->integer('id_nasabah');
            $table->foreign('id_nasabah')->references('id_nasabah')->on('nasabah')->onDelete('cascade');

            // Relasi ke hadiah
            $table->unsignedBigInteger('id_hadiah');
            $table->foreign('id_hadiah')->references('id_hadiah')->on('hadiah')->onDelete('cascade');

            $table->integer('jumlah')->default(1);
            $table->integer('total_poin_ditukar');
            $table->enum('status', ['menunggu', 'diambil', 'ditolak'])->default('menunggu');
            $table->text('catatan')->nullable();
            $table->date('tanggal_tukar');
            
            // Relasi ke admin verifikator
            $table->integer('id_admin')->nullable();
            $table->foreign('id_admin')->references('id_admin')->on('admin')->onDelete('set null');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penukaran_reward');
        Schema::dropIfExists('hadiah');
    }
};
