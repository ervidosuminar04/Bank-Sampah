<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Gamifikasi extends Model
{
    protected $table = 'gamifikasi';
    protected $primaryKey = 'id_reward';
    public $timestamps = false;

    protected $fillable = [
        'poin_diperoleh',
        'total_poin',
        'level_nasabah',
        'badge',
        'tanggal_update',
        'id_nasabah',
    ];

    /**
     * Get the nasabah who owns this gamification/rewards profile.
     */
    public function nasabah(): BelongsTo
    {
        return $this->belongsTo(Nasabah::class, 'id_nasabah', 'id_nasabah');
    }
}
