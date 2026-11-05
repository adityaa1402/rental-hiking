<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailTransaksi extends Model
{
    protected $table = 'detail_transaksi';
    protected $primaryKey = 'id_detail';
    public $timestamps = false;

    protected $fillable = [
        'id_transaksi', 'id_alat', 'jumlah_unit',
        'harga_per_hari_snapshot', 'subtotal',
    ];

    public function transaksi(): BelongsTo
    {
        return $this->belongsTo(Transaksi::class, 'id_transaksi', 'id_transaksi');
    }

    public function alat(): BelongsTo
    {
        return $this->belongsTo(AlatHiking::class, 'id_alat', 'id_alat');
    }

    // Alias untuk konsistensi penamaan
    public function alatHiking(): BelongsTo
    {
        return $this->alat();
    }
}

