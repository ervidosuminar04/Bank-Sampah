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
    public $timestamps    = true;

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

    /** Accessor untuk transaksi_ids agar selalu didecode menjadi array */
    public function getTransaksiIdsAttribute($value)
    {
        if (is_array($value)) {
            return $value;
        }
        return json_decode($value, true) ?: [];
    }

    /** Mutator untuk transaksi_ids agar bisa menerima array maupun string json */
    public function setTransaksiIdsAttribute($value)
    {
        $this->attributes['transaksi_ids'] = is_array($value) ? json_encode($value) : $value;
    }

    /** Virtual attribute ->id mapping ke id_setoran_pengepul untuk kompatibilitas view */
    public function getIdAttribute()
    {
        return $this->id_setoran_pengepul;
    }

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
