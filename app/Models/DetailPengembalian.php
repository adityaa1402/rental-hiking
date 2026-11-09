<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailPengembalian extends Model
{
    protected $table = 'detail_pengembalian';
    protected $primaryKey = 'id_detail_pengembalian';
    public $timestamps = false;

    protected $fillable = [
        'id_pengembalian', 'id_alat', 'jumlah_baik', 'jumlah_rusak', 'jumlah_hilang',
    ];

    public function pengembalian(): BelongsTo
    {
        return $this->belongsTo(Pengembalian::class, 'id_pengembalian', 'id_pengembalian');
    }

    public function alatHiking(): BelongsTo
    {
        return $this->belongsTo(AlatHiking::class, 'id_alat', 'id_alat');
    }
}
