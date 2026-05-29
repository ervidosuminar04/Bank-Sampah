<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Admin extends Model
{
    protected $table = 'admin';
    protected $primaryKey = 'id_admin';
    public $timestamps = false;

    protected $fillable = [
        'admin_nama',
        'admin_username',
        'admin_password',
        'admin_level',
        'admin_status',
    ];

    protected $hidden = [
        'admin_password',
    ];

    /**
     * Get geolocations managed by this admin.
     */
    public function geolokasi(): HasMany
    {
        return $this->hasMany(Geolokasi::class, 'id_admin', 'id_admin');
    }

    /**
     * Get deposit transactions processed by this admin.
     */
    public function transaksiSetor(): HasMany
    {
        return $this->hasMany(TransaksiSetor::class, 'id_admin', 'id_admin');
    }

    /**
     * Get withdrawal transactions processed by this admin.
     */
    public function transaksiTarik(): HasMany
    {
        return $this->hasMany(TransaksiTarik::class, 'id_admin', 'id_admin');
    }

    /**
     * Get reports created by this admin.
     */
    public function laporan(): HasMany
    {
        return $this->hasMany(Laporan::class, 'id_admin', 'id_admin');
    }
}
