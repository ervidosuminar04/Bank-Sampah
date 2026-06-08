<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // ---------------------------------------------------------
        // 0. Drop foreign keys referencing pengepul.id so we can alter it
        // ---------------------------------------------------------
        Schema::table('transaksi_pengepul', function (Blueprint $table) {
            $table->dropForeign('transaksi_pengepul_pengepul_id_foreign');
        });
        Schema::table('setoran_pengepul', function (Blueprint $table) {
            $table->dropForeign('setoran_pengepul_pengepul_id_foreign');
        });

        // ---------------------------------------------------------
        // 1. Table: pengepul
        // ---------------------------------------------------------
        // Rename PK id -> id_pengepul, type to INT UNSIGNED AUTO_INCREMENT
        DB::statement('ALTER TABLE pengepul CHANGE `id` `id_pengepul` INT UNSIGNED NOT NULL AUTO_INCREMENT');
        // Rename columns
        DB::statement('ALTER TABLE pengepul CHANGE `nama` `pengepul_nama` VARCHAR(100) NOT NULL');
        DB::statement('ALTER TABLE pengepul CHANGE `alamat` `pengepul_alamat` TEXT NOT NULL');
        DB::statement('ALTER TABLE pengepul CHANGE `telepon` `pengepul_telepon` VARCHAR(20) NULL');
        DB::statement('ALTER TABLE pengepul CHANGE `username` `pengepul_username` VARCHAR(50) NOT NULL');
        DB::statement('ALTER TABLE pengepul CHANGE `password` `pengepul_password` VARCHAR(255) NOT NULL');
        DB::statement('ALTER TABLE pengepul CHANGE `latitude` `pengepul_latitude` DECIMAL(10,8) NULL');
        DB::statement('ALTER TABLE pengepul CHANGE `longitude` `pengepul_longitude` DECIMAL(11,8) NULL');
        DB::statement('ALTER TABLE pengepul CHANGE `komisi_persen` `pengepul_komisi_persen` DECIMAL(5,2) NOT NULL DEFAULT 50');
        // Rename status_aktif from boolean to varchar(10)
        DB::statement("ALTER TABLE pengepul CHANGE `status_aktif` `pengepul_status_aktif` VARCHAR(10) NOT NULL DEFAULT 'aktif'");

        // ---------------------------------------------------------
        // 2. Table: sampah
        // ---------------------------------------------------------
        DB::statement('ALTER TABLE sampah CHANGE `sampah_name` `sampah_nama` VARCHAR(100) NOT NULL');

        // ---------------------------------------------------------
        // 3. Table: geolokasi
        // ---------------------------------------------------------
        DB::statement('ALTER TABLE geolokasi CHANGE `alamat` `geolokasi_alamat` VARCHAR(255) NOT NULL');

        // ---------------------------------------------------------
        // 4. Table: transaksi_tarik
        // ---------------------------------------------------------
        // Rename tarik_* columns
        DB::statement('ALTER TABLE transaksi_tarik CHANGE `tarik_tanggal` `transaksi_tarik_tanggal` DATE NOT NULL');
        DB::statement('ALTER TABLE transaksi_tarik CHANGE `tarik_jumlah` `transaksi_tarik_jumlah` DECIMAL(15,2) NOT NULL');
        DB::statement('ALTER TABLE transaksi_tarik CHANGE `tarik_bank_tujuan` `transaksi_tarik_bank_tujuan` VARCHAR(50) NULL');
        DB::statement('ALTER TABLE transaksi_tarik CHANGE `tarik_nomor_rekening` `transaksi_tarik_nomor_rekening` VARCHAR(50) NULL');
        DB::statement('ALTER TABLE transaksi_tarik CHANGE `tarik_atas_nama` `transaksi_tarik_atas_nama` VARCHAR(100) NULL');
        DB::statement('ALTER TABLE transaksi_tarik CHANGE `tarik_sisa_saldo` `transaksi_tarik_sisa_saldo` DECIMAL(15,2) NOT NULL');
        DB::statement("ALTER TABLE transaksi_tarik CHANGE `status` `transaksi_tarik_status` VARCHAR(20) NOT NULL DEFAULT 'menunggu'");
        DB::statement('ALTER TABLE transaksi_tarik CHANGE `catatan` `transaksi_tarik_catatan` TEXT NULL');

        // ---------------------------------------------------------
        // 5. Table: transaksi_pengepul
        // ---------------------------------------------------------
        DB::statement('ALTER TABLE transaksi_pengepul CHANGE `id` `id_transaksi_pengepul` INT UNSIGNED NOT NULL AUTO_INCREMENT');
        DB::statement('ALTER TABLE transaksi_pengepul CHANGE `komisi_pengepul` `transaksi_pengepul_komisi_pengepul` DECIMAL(12,2) NOT NULL DEFAULT 0');
        DB::statement('ALTER TABLE transaksi_pengepul CHANGE `tanggal` `transaksi_pengepul_tanggal` DATE NOT NULL');
        DB::statement('ALTER TABLE transaksi_pengepul CHANGE `keterangan` `transaksi_pengepul_keterangan` TEXT NULL');
        DB::statement('ALTER TABLE transaksi_pengepul CHANGE `foto_dokumentasi` `transaksi_pengepul_foto` VARCHAR(255) NULL');
        // Modify pengepul_id column type to match the new INT UNSIGNED PK
        DB::statement('ALTER TABLE transaksi_pengepul MODIFY `pengepul_id` INT UNSIGNED NOT NULL');

        // ---------------------------------------------------------
        // 6. Table: setoran_pengepul
        // ---------------------------------------------------------
        DB::statement('ALTER TABLE setoran_pengepul CHANGE `id` `id_setoran_pengepul` INT UNSIGNED NOT NULL AUTO_INCREMENT');
        DB::statement("ALTER TABLE setoran_pengepul CHANGE `status` `setoran_pengepul_status` VARCHAR(20) NOT NULL DEFAULT 'menunggu'");
        DB::statement('ALTER TABLE setoran_pengepul CHANGE `foto_dokumentasi` `setoran_pengepul_gambar` VARCHAR(255) NULL');
        DB::statement('ALTER TABLE setoran_pengepul MODIFY `transaksi_ids` TEXT NOT NULL');
        // Modify pengepul_id column type to match the new INT UNSIGNED PK
        DB::statement('ALTER TABLE setoran_pengepul MODIFY `pengepul_id` INT UNSIGNED NOT NULL');

        // ---------------------------------------------------------
        // 7. Table: admin
        // ---------------------------------------------------------
        DB::statement('ALTER TABLE admin DROP COLUMN admin_level');

        // ---------------------------------------------------------
        // Re-create foreign keys pointing to pengepul
        // ---------------------------------------------------------
        DB::statement('ALTER TABLE transaksi_pengepul ADD CONSTRAINT transaksi_pengepul_pengepul_id_foreign FOREIGN KEY (`pengepul_id`) REFERENCES `pengepul` (`id_pengepul`) ON DELETE CASCADE');
        DB::statement('ALTER TABLE setoran_pengepul ADD CONSTRAINT setoran_pengepul_pengepul_id_foreign FOREIGN KEY (`pengepul_id`) REFERENCES `pengepul` (`id_pengepul`) ON DELETE CASCADE');

        // ---------------------------------------------------------
        // 8. Table: hadiah
        // ---------------------------------------------------------
        DB::statement('ALTER TABLE penukaran_reward DROP FOREIGN KEY penukaran_reward_id_hadiah_foreign');
        DB::statement('ALTER TABLE hadiah CHANGE `id_hadiah` `id_hadiah` INT UNSIGNED NOT NULL AUTO_INCREMENT');
        DB::statement('ALTER TABLE hadiah CHANGE `nama_hadiah` `hadiah_nama`        VARCHAR(100)  NOT NULL');
        DB::statement('ALTER TABLE hadiah CHANGE `poin_butuh`  `hadiah_poin_butuh`  INT          NOT NULL DEFAULT 0');
        DB::statement('ALTER TABLE hadiah CHANGE `stok`        `hadiah_stok`        INT          NOT NULL DEFAULT 0');
        DB::statement('ALTER TABLE hadiah CHANGE `gambar`      `hadiah_gambar`      VARCHAR(255) NULL');
        DB::statement('ALTER TABLE hadiah CHANGE `keterangan`  `hadiah_keterangan`  TEXT         NULL');

        // ---------------------------------------------------------
        // 9. Table: penukaran_reward
        // ---------------------------------------------------------
        DB::statement('ALTER TABLE penukaran_reward MODIFY `id_hadiah` INT UNSIGNED NOT NULL');
        DB::statement('ALTER TABLE penukaran_reward CHANGE `id_penukaran` `id_penukaran` INT UNSIGNED NOT NULL AUTO_INCREMENT');
        DB::statement("ALTER TABLE penukaran_reward CHANGE `status` `penukaran_status` VARCHAR(20) NOT NULL DEFAULT 'menunggu'");

        // Re-add FK for penukaran_reward pointing to hadiah
        DB::statement('ALTER TABLE penukaran_reward ADD CONSTRAINT penukaran_reward_id_hadiah_foreign FOREIGN KEY (`id_hadiah`) REFERENCES `hadiah` (`id_hadiah`) ON DELETE CASCADE');

        // ---------------------------------------------------------
        // 10. Table: nasabah
        // ---------------------------------------------------------
        DB::statement("ALTER TABLE nasabah MODIFY `nasabah_status` VARCHAR(20) DEFAULT 'aktif'");
    }

    public function down(): void
    {
        // No down method needed as we run fresh migrations
    }
};
