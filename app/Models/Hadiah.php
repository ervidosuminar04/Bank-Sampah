<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hadiah extends Model
{
    use HasFactory;

    protected $table      = 'hadiah';
    protected $primaryKey = 'id_hadiah';
    public $timestamps    = false;

    protected $fillable = [
        'hadiah_nama',
        'hadiah_poin_butuh',
        'hadiah_stok',
        'hadiah_gambar',
        'hadiah_keterangan',
    ];

    /** Penukaran reward oleh nasabah. */
    public function penukaran()
    {
        return $this->hasMany(PenukaranReward::class, 'id_hadiah', 'id_hadiah');
    }
}
