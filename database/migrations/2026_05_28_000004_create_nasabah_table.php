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
        Schema::create('nasabah', function (Blueprint $table) {
            $table->integer('id_nasabah')->autoIncrement();
            $table->string('nasabah_nama', 100);
            $table->string('nasabah_nik', 20);
            $table->text('nasabah_alamat');
            $table->string('nasabah_telepon', 20);
            $table->string('nasabah_email', 100);
            $table->string('nasabah_username', 50)->unique();
            $table->string('nasabah_password', 255);
            $table->string('nasabah_foto', 255)->nullable();
            $table->decimal('nasabah_saldo', 15, 2);
            $table->date('nasabah_tgl_daftar');
            
            // Relasi ke geolokasi (ditempati_oleh)
            $table->integer('id_lokasi')->nullable();
            $table->foreign('id_lokasi')->references('id_lokasi')->on('geolokasi')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::downIfExists('nasabah');
    }
};
