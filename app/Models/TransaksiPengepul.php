<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiPengepul extends Model
{
    use HasFactory;

    protected $table = 'transaksi_pengepul';

    protected $fillable = [
        'pengepul_id',
        'nasabah_id',
        'id_sampah',
        'berat_kg',
        'harga_beli_kg',
        'harga_pasar_kg',
        'nilai_idr',
        'selisih_total',
        'komisi_pengepul',
        'bagian_admin',
        'sudah_disetor',
        'tanggal',
        'keterangan',
    ];

    protected $casts = [
        'sudah_disetor' => 'boolean',
    ];

    public function pengepul()
    {
        return $this->belongsTo(Pengepul::class, 'pengepul_id');
    }

    public function nasabah()
    {
        return $this->belongsTo(Nasabah::class, 'nasabah_id', 'id_nasabah');
    }

    public function sampah()
    {
        return $this->belongsTo(Sampah::class, 'id_sampah', 'id_sampah');
    }

    /**
     * Scope: hanya transaksi yang belum disetor ke admin.
     */
    public function scopeBelumDisetor($query)
    {
        return $query->where('sudah_disetor', false);
    }

    /**
     * Scope: hanya transaksi yang sudah disetor ke admin.
     */
    public function scopeSudahDisetor($query)
    {
        return $query->where('sudah_disetor', true);
    }
}
