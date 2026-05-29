<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransaksiTarik extends Model
{
    protected $table = 'transaksi_tarik';
    protected $primaryKey = 'id_tarik';

    protected $fillable = [
        'tarik_tanggal',
        'tarik_jumlah',
        'tarik_bank_tujuan',
        'tarik_nomor_rekening',
        'tarik_atas_nama',
        'tarik_sisa_saldo',
        'status',
        'catatan',
        'id_nasabah',
        'id_admin',
    ];

    /**
     * Nasabah yang mengajukan pencairan.
     */
    public function nasabah(): BelongsTo
    {
        return $this->belongsTo(Nasabah::class, 'id_nasabah', 'id_nasabah');
    }

    /**
     * Admin yang memproses pencairan.
     */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'id_admin', 'id_admin');
    }
}
