<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SetoranPengepul extends Model
{
    use HasFactory;

    protected $table = 'setoran_pengepul';

    protected $fillable = [
        'pengepul_id',
        'total_nilai_nasabah',
        'total_selisih',
        'total_komisi_pengepul',
        'total_bagian_admin',
        'total_disetor',
        'transaksi_ids',
        'status',
        'id_admin',
        'catatan',
        'foto_dokumentasi',
    ];

    protected $casts = [
        'transaksi_ids' => 'array',
    ];

    /**
     * Pengepul yang melakukan setoran.
     */
    public function pengepul(): BelongsTo
    {
        return $this->belongsTo(Pengepul::class, 'pengepul_id');
    }

    /**
     * Admin yang memverifikasi setoran.
     */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'id_admin', 'id_admin');
    }

    /**
     * Scope: setoran yang menunggu verifikasi.
     */
    public function scopeMenunggu($query)
    {
        return $query->where('status', 'menunggu');
    }

    /**
     * Scope: setoran yang sudah terverifikasi.
     */
    public function scopeTerverifikasi($query)
    {
        return $query->where('status', 'terverifikasi');
    }
}
