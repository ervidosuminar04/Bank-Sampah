<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenukaranReward extends Model
{
    use HasFactory;

    protected $table = 'penukaran_reward';
    protected $primaryKey = 'id_penukaran';

    protected $fillable = [
        'id_nasabah',
        'id_hadiah',
        'jumlah',
        'total_poin_ditukar',
        'status',
        'catatan',
        'tanggal_tukar',
        'id_admin',
    ];

    /**
     * Nasabah yang mengajukan penukaran.
     */
    public function nasabah()
    {
        return $this->belongsTo(Nasabah::class, 'id_nasabah', 'id_nasabah');
    }

    /**
     * Hadiah yang ditukarkan.
     */
    public function hadiah()
    {
        return $this->belongsTo(Hadiah::class, 'id_hadiah', 'id_hadiah');
    }

    /**
     * Admin yang memproses penukaran.
     */
    public function admin()
    {
        return $this->belongsTo(Admin::class, 'id_admin', 'id_admin');
    }
}
