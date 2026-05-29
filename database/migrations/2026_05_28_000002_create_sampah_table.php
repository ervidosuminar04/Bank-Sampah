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
        Schema::create('sampah', function (Blueprint $table) {
            $table->integer('id_sampah')->autoIncrement();
            $table->string('sampah_name', 100);
            $table->string('sampah_jenis', 20);
            $table->string('sampah_satuan', 10);
            $table->decimal('sampah_harga_kg', 10, 2);
            $table->string('sampah_gambar', 255)->nullable();
            $table->text('sampah_keterangan')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::downIfExists('sampah');
    }
};
