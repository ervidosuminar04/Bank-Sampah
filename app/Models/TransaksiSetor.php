<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransaksiSetor extends Model
{
    protected $table = 'transaksi_setor';
    protected $primaryKey = 'id_setor';
    public $timestamps = false;

    protected $fillable = [
        'setor_tanggal',
        'setor_berat_kg',
        'setor_harga_total',
        'setor_keterangan',
        'id_nasabah',
        'id_admin',
        'id_sampah',
        'transaksi_setor_gambar',
    ];

    /**
     * Get the nasabah who performed this deposit transaction.
     */
    public function nasabah(): BelongsTo
    {
        return $this->belongsTo(Nasabah::class, 'id_nasabah', 'id_nasabah');
    }

    /**
     * Get the admin who processed this deposit transaction.
     */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'id_admin', 'id_admin');
    }

    /**
     * Get the garbage type of this deposit transaction.
     */
    public function sampah(): BelongsTo
    {
        return $this->belongsTo(Sampah::class, 'id_sampah', 'id_sampah');
    }
}
