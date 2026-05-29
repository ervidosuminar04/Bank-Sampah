<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tabungan extends Model
{
    protected $table = 'tabungan';
    protected $primaryKey = 'id_tabungan';
    public $timestamps = false;

    protected $fillable = [
        'tabungan_no_rekening',
        'tabungan_total_setor',
        'tabungan_total_tarik',
        'tabungan_saldo_akhir',
        'tabungan_tgl_update',
        'id_nasabah',
    ];

    /**
     * Get the nasabah who owns this savings account.
     */
    public function nasabah(): BelongsTo
    {
        return $this->belongsTo(Nasabah::class, 'id_nasabah', 'id_nasabah');
    }
}
