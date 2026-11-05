<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AlatHiking extends Model
{
    protected $table = 'alat_hiking';
    protected $primaryKey = 'id_alat';
    public $timestamps = true;

    protected $fillable = [
        'id_kategori', 'nama_alat', 'slug', 'merk', 'deskripsi',
        'spesifikasi', 'harga_per_hari', 'stok_total', 'kondisi', 'gambar',
    ];

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class, 'id_kategori', 'id_kategori');
    }

    public function detailTransaksi(): HasMany
    {
        return $this->hasMany(DetailTransaksi::class, 'id_alat', 'id_alat');
    }

    public function detailPaket(): HasMany
    {
        return $this->hasMany(DetailPaket::class, 'id_alat', 'id_alat');
    }

    /**
     * Hitung sisa stok yang tersedia pada rentang tanggal tertentu
     */
    public function getSisaStok(?string $startDate = null, ?string $endDate = null, ?int $excludeTransaksiId = null): int
    {
        if ($this->kondisi !== 'Baik') return 0;

        if (!$startDate || !$endDate) return $this->stok_total;

        $query = DetailTransaksi::query()
            ->join('transaksi', 'detail_transaksi.id_transaksi', '=', 'transaksi.id_transaksi')
            ->where('detail_transaksi.id_alat', $this->id_alat)
            ->whereIn('transaksi.status_transaksi', ['Disetujui', 'Berjalan'])
            ->where('transaksi.tgl_sewa', '<=', $endDate)
            ->where('transaksi.tgl_kembali', '>=', $startDate);

        if ($excludeTransaksiId) {
            $query->where('transaksi.id_transaksi', '!=', $excludeTransaksiId);
        }

        $terpakai = (int) $query->sum('detail_transaksi.jumlah_unit');

        return max(0, $this->stok_total - $terpakai);
    }
}

