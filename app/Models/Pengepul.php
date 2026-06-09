<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pengepul extends Model
{
    protected $table      = 'pengepul';
    protected $primaryKey = 'id_pengepul';
    public $timestamps    = false;

    protected $fillable = [
        'pengepul_nama',
        'pengepul_alamat',
        'pengepul_telepon',
        'pengepul_username',
        'pengepul_password',
        'pengepul_latitude',
        'pengepul_longitude',
        'pengepul_status_aktif',
        'pengepul_komisi_persen',
    ];

    protected $hidden = ['pengepul_password'];

    /** Transaksi timbangan dari nasabah. */
    public function transaksi(): HasMany
    {
        return $this->hasMany(TransaksiPengepul::class, 'pengepul_id', 'id_pengepul');
    }

    /** Transaksi yang belum dimasukkan ke setoran. */
    public function transaksiBelumDisetor(): HasMany
    {
        return $this->hasMany(TransaksiPengepul::class, 'pengepul_id', 'id_pengepul')
                    ->where('sudah_disetor', false);
    }

    /** Setoran pengepul ke admin. */
    public function setoran(): HasMany
    {
        return $this->hasMany(SetoranPengepul::class, 'pengepul_id', 'id_pengepul');
    }
}
