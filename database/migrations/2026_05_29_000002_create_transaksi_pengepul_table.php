<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('transaksi_pengepul', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('pengepul_id');
            $table->unsignedBigInteger('nasabah_id');
            $table->unsignedBigInteger('id_sampah');
            $table->decimal('berat_kg', 8, 2);
            $table->decimal('nilai_idr', 12, 2);
            $table->date('tanggal');
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->foreign('pengepul_id')->references('id')->on('pengepul')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksi_pengepul');
    }
};
