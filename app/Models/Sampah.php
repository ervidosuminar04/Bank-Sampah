<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sampah extends Model
{
    protected $table = 'sampah';
    protected $primaryKey = 'id_sampah';
    public $timestamps = false;

    protected $fillable = [
        'sampah_name',
        'sampah_jenis',
        'sampah_satuan',
        'sampah_harga_kg',
        'sampah_harga_pasar',
        'sampah_gambar',
        'sampah_keterangan',
    ];

    /**
     * Get deposit transactions containing this garbage type.
     */
    public function transaksiSetor(): HasMany
    {
        return $this->hasMany(TransaksiSetor::class, 'id_sampah', 'id_sampah');
    }

    /**
     * Get pengepul transactions involving this garbage type.
     */
    public function transaksiPengepul(): HasMany
    {
        return $this->hasMany(TransaksiPengepul::class, 'id_sampah', 'id_sampah');
    }
}
