<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengepul', function (Blueprint $table) {
            // Persentase komisi yang diterima pengepul dari selisih harga pasar - harga beli
            $table->decimal('komisi_persen', 5, 2)->default(50)->after('status_aktif');
        });
    }

    public function down(): void
    {
        Schema::table('pengepul', function (Blueprint $table) {
            $table->dropColumn('komisi_persen');
        });
    }
};
