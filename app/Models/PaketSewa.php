<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PaketSewa extends Model
{
    protected $table = 'paket_sewa';
    protected $primaryKey = 'id_paket';
    public $timestamps = true;

    protected $fillable = [
        'nama_paket', 'slug', 'deskripsi', 'kapasitas_orang',
        'harga_paket_per_hari', 'gambar', 'status_aktif',
    ];

    public function detailPaket(): HasMany
    {
        return $this->hasMany(DetailPaket::class, 'id_paket', 'id_paket');
    }
}

