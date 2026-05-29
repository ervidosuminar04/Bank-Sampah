<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Nasabah extends Model
{
    protected $table = 'nasabah';
    protected $primaryKey = 'id_nasabah';
    public $timestamps = false;

    protected $fillable = [
        'nasabah_nama',
        'nasabah_nik',
        'nasabah_alamat',
        'nasabah_telepon',
        'nasabah_email',
        'nasabah_username',
        'nasabah_password',
        'nasabah_foto',
        'nasabah_saldo',
        'nasabah_tgl_daftar',
        'id_lokasi',
        'nasabah_status',
    ];

    protected $hidden = [
        'nasabah_password',
    ];

    /**
     * Get the geolocation occupied by this nasabah.
     */
    public function geolokasi(): BelongsTo
    {
        return $this->belongsTo(Geolokasi::class, 'id_lokasi', 'id_lokasi');
    }

    /**
     * Get deposit transactions made by this nasabah.
     */
    public function transaksiSetor(): HasMany
    {
        return $this->hasMany(TransaksiSetor::class, 'id_nasabah', 'id_nasabah');
    }

    /**
     * Get withdrawal transactions made by this nasabah.
     */
    public function transaksiTarik(): HasMany
    {
        return $this->hasMany(TransaksiTarik::class, 'id_nasabah', 'id_nasabah');
    }

    /**
     * Get the savings account of this nasabah.
     */
    public function tabungan(): HasOne
    {
        return $this->hasOne(Tabungan::class, 'id_nasabah', 'id_nasabah');
    }

    /**
     * Get the gamification/rewards profile of this nasabah.
     */
    public function gamifikasi(): HasOne
    {
        return $this->hasOne(Gamifikasi::class, 'id_nasabah', 'id_nasabah');
    }

    /**
     * Get reward redemptions made by this nasabah.
     */
    public function penukaranReward(): HasMany
    {
        return $this->hasMany(PenukaranReward::class, 'id_nasabah', 'id_nasabah');
    }
}
