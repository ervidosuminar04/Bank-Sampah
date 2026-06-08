<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransaksiTarik extends Model
{
    protected $table      = 'transaksi_tarik';
    protected $primaryKey = 'id_tarik';
    public $timestamps    = false;

    protected $fillable = [
        'transaksi_tarik_tanggal',
        'transaksi_tarik_jumlah',
        'transaksi_tarik_bank_tujuan',
        'transaksi_tarik_nomor_rekening',
        'transaksi_tarik_atas_nama',
        'transaksi_tarik_sisa_saldo',
        'transaksi_tarik_status',
        'transaksi_tarik_catatan',
        'transaksi_tarik_gambar',
        'id_nasabah',
        'id_admin',
    ];

    /** Nasabah yang mengajukan pencairan. */
    public function nasabah(): BelongsTo
    {
        return $this->belongsTo(Nasabah::class, 'id_nasabah', 'id_nasabah');
    }

    /** Admin yang memproses pencairan. */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'id_admin', 'id_admin');
    }
}
