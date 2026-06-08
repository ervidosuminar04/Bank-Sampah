<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransaksiPengepul extends Model
{
    use HasFactory;

    protected $table      = 'transaksi_pengepul';
    protected $primaryKey = 'id_transaksi_pengepul';
    public $timestamps    = false;

    protected $fillable = [
        'pengepul_id',
        'nasabah_id',
        'id_sampah',
        'berat_kg',
        'harga_beli_kg',
        'harga_pasar_kg',
        'nilai_idr',
        'selisih_total',
        'transaksi_pengepul_komisi_pengepul',
        'bagian_admin',
        'sudah_disetor',
        'transaksi_pengepul_tanggal',
        'transaksi_pengepul_keterangan',
        'transaksi_pengepul_gambar',
    ];

    protected $casts = [
        'sudah_disetor' => 'boolean',
    ];

    public function pengepul(): BelongsTo
    {
        return $this->belongsTo(Pengepul::class, 'pengepul_id', 'id_pengepul');
    }

    public function nasabah(): BelongsTo
    {
        return $this->belongsTo(Nasabah::class, 'nasabah_id', 'id_nasabah');
    }

    public function sampah(): BelongsTo
    {
        return $this->belongsTo(Sampah::class, 'id_sampah', 'id_sampah');
    }

    /** Scope: hanya transaksi yang belum disetor ke admin. */
    public function scopeBelumDisetor($query)
    {
        return $query->where('sudah_disetor', false);
    }

    /** Scope: hanya transaksi yang sudah disetor ke admin. */
    public function scopeSudahDisetor($query)
    {
        return $query->where('sudah_disetor', true);
    }
}
