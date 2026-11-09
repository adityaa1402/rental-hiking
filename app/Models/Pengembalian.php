<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pengembalian extends Model
{
    protected $table = 'pengembalian';
    protected $primaryKey = 'id_pengembalian';
    public $timestamps = false;
    const CREATED_AT = 'created_at';

    protected $fillable = [
        'id_transaksi', 'tanggal_pengembalian_aktual', 'kondisi_pengembalian',
        'jumlah_baik', 'jumlah_rusak', 'jumlah_hilang', 'denda', 'catatan', 'id_admin',
    ];

    public function transaksi(): BelongsTo
    {
        return $this->belongsTo(Transaksi::class, 'id_transaksi', 'id_transaksi');
    }

    public function detailPengembalian(): HasMany
    {
        return $this->hasMany(DetailPengembalian::class, 'id_pengembalian', 'id_pengembalian');
    }
}
