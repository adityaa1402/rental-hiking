<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kategori extends Model
{
    protected $table = 'kategori';
    protected $primaryKey = 'id_kategori';
    public $timestamps = false;

    protected $fillable = ['nama_kategori', 'slug', 'deskripsi', 'icon'];

    public function alatHiking(): HasMany
    {
        return $this->hasMany(AlatHiking::class, 'id_kategori', 'id_kategori');
    }
}

