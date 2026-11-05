<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DokumenJaminan extends Model
{
    protected $table = 'dokumen_jaminan';
    protected $primaryKey = 'id_dokumen';
    public $timestamps = false;
    const CREATED_AT = 'created_at';

    protected $fillable = ['id_transaksi', 'nama_file_tersimpan', 'tipe_dokumen'];

    public function transaksi(): BelongsTo
    {
        return $this->belongsTo(Transaksi::class, 'id_transaksi', 'id_transaksi');
    }
}

