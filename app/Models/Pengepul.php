<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengepul extends Model
{
    use HasFactory;

    protected $table = 'pengepul';

    protected $fillable = [
        'nama',
        'alamat',
        'telepon',
        'username',
        'password',
        'latitude',
        'longitude',
        'status_aktif',
        'komisi_persen',
    ];

    protected $hidden = ['password'];

    /**
     * Transaksi timbangan dari nasabah.
     */
    public function transaksi()
    {
        return $this->hasMany(TransaksiPengepul::class, 'pengepul_id');
    }

    /**
     * Transaksi yang belum dimasukkan ke setoran.
     */
    public function transaksiBelumDisetor()
    {
        return $this->hasMany(TransaksiPengepul::class, 'pengepul_id')
                    ->where('sudah_disetor', false);
    }

    /**
     * Setoran pengepul ke admin.
     */
    public function setoran()
    {
        return $this->hasMany(SetoranPengepul::class, 'pengepul_id');
    }
}
