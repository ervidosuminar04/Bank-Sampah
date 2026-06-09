<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DetailTransaksiSetor extends Model
{
    protected $table      = 'detail_transaksi_setor';
    protected $primaryKey = 'id_detail';
    public $timestamps    = false;

    protected $fillable = [
        'id_setor',
        'id_sampah',
        'berat_kg',
    ];

    public function transaksiSetor(): BelongsTo
    {
        return $this->belongsTo(TransaksiSetor::class, 'id_setor', 'id_setor');
    }

    public function sampah(): BelongsTo
    {
        return $this->belongsTo(Sampah::class, 'id_sampah', 'id_sampah');
    }
}
