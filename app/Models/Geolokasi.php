<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Geolokasi extends Model
{
    protected $table = 'geolokasi';
    protected $primaryKey = 'id_lokasi';
    public $timestamps = false;

    protected $fillable = [
        'nama_lokasi',
        'alamat',
        'latitude',
        'longitude',
        'jam_operasional',
        'status_aktif',
        'id_admin',
    ];

    /**
     * Get the admin who manages this geolocation.
     */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'id_admin', 'id_admin');
    }

    /**
     * Get nasabah at this geolocation.
     */
    public function nasabah(): HasMany
    {
        return $this->hasMany(Nasabah::class, 'id_lokasi', 'id_lokasi');
    }
}
