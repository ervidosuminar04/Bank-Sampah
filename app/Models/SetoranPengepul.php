<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SetoranPengepul extends Model
{
    use HasFactory;

    protected $table      = 'setoran_pengepul';
    protected $primaryKey = 'id_setoran_pengepul';
    public $timestamps    = false;

    protected $fillable = [
        'pengepul_id',
        'total_nilai_nasabah',
        'total_selisih',
        'total_komisi_pengepul',
        'total_bagian_admin',
        'total_disetor',
        'transaksi_ids',
        'setoran_pengepul_status',
        'id_admin',
        'catatan',
        'setoran_pengepul_gambar',
    ];

    // transaksi_ids disimpan sebagai TEXT, decode manual saat digunakan
    protected $casts = [];

    /** Pengepul yang melakukan setoran. */
    public function pengepul(): BelongsTo
    {
        return $this->belongsTo(Pengepul::class, 'pengepul_id', 'id_pengepul');
    }

    /** Admin yang memverifikasi setoran. */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'id_admin', 'id_admin');
    }

    /** Scope: setoran yang menunggu verifikasi. */
    public function scopeMenunggu($query)
    {
        return $query->where('setoran_pengepul_status', 'menunggu');
    }

    /** Scope: setoran yang sudah terverifikasi. */
    public function scopeTerverifikasi($query)
    {
        return $query->where('setoran_pengepul_status', 'terverifikasi');
    }
}
