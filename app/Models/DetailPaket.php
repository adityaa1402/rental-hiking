<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailPaket extends Model
{
    protected $table = 'detail_paket';
    protected $primaryKey = 'id_detail_paket';
    public $timestamps = false;

    protected $fillable = ['id_paket', 'id_alat', 'jumlah_unit'];

    public function paket(): BelongsTo
    {
        return $this->belongsTo(PaketSewa::class, 'id_paket', 'id_paket');
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

