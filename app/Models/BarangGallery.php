<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BarangGallery extends Model
{
    protected $table = 'barang_gallery';
    protected $primaryKey = 'id_gallery';
    public $timestamps = true;
    const UPDATED_AT = null;

    protected $fillable = [
        'id_alat', 'gambar', 'keterangan', 'urutan',
    ];

    public function alatHiking(): BelongsTo
    {
        return $this->belongsTo(AlatHiking::class, 'id_alat', 'id_alat');
    }
}
