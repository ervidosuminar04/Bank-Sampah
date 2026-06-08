<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Laporan extends Model
{
    protected $table      = 'laporan';
    protected $primaryKey = 'id_laporan';
    public $timestamps    = false;

    protected $fillable = [
        'laporan_jenis',
        'laporan_periode',
        'laporan_total_kg',
        'laporan_total_nilai',
        'laporan_tgl_buat',
        'id_admin',
    ];

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'id_admin', 'id_admin');
    }
}
