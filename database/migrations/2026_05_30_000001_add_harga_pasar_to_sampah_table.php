<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sampah', function (Blueprint $table) {
            // Harga pasar = harga jual ke luar (lebih tinggi dari harga beli nasabah)
            $table->decimal('sampah_harga_pasar', 10, 2)->default(0)->after('sampah_harga_kg');
        });
    }

    public function down(): void
    {
        Schema::table('sampah', function (Blueprint $table) {
            $table->dropColumn('sampah_harga_pasar');
        });
    }
};
