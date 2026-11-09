<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\Builder as QueryBuilder;

class AlatHiking extends Model
{
    protected $table = 'alat_hiking';
    protected $primaryKey = 'id_alat';
    public $timestamps = true;

    protected $fillable = [
        'id_kategori', 'nama_alat', 'slug', 'merk', 'deskripsi',
        'spesifikasi', 'harga_per_hari', 'stok_total', 'stok_rusak', 'kondisi', 'gambar',
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

    public function gallery(): HasMany
    {
        return $this->hasMany(BarangGallery::class, 'id_alat', 'id_alat')->orderBy('urutan');
    }

    public function scopeWithoutDuplicateListings(Builder $query): Builder
    {
        return $query->whereNotExists(function (QueryBuilder $duplicates): void {
            $duplicates->selectRaw('1')
                ->from('alat_hiking as duplicate_alat')
                ->whereColumn('duplicate_alat.nama_alat', 'alat_hiking.nama_alat')
                ->whereColumn('duplicate_alat.merk', 'alat_hiking.merk')
                ->whereColumn('duplicate_alat.id_kategori', 'alat_hiking.id_kategori')
                ->whereColumn('duplicate_alat.kondisi', 'alat_hiking.kondisi')
                ->whereColumn('duplicate_alat.id_alat', '<', 'alat_hiking.id_alat');
        });
    }

    /**
     * Hitung sisa stok yang tersedia pada rentang tanggal tertentu
     */
    public function getSisaStok(?string $startDate = null, ?string $endDate = null, ?int $excludeTransaksiId = null): int
    {
        if ($this->kondisi !== 'Baik') return 0;

        if (!$startDate || !$endDate) {
            return max(0, (int) $this->stok_total - (int) $this->stok_rusak);
        }

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

        return max(0, (int) $this->stok_total - (int) $this->stok_rusak - $terpakai);
    }
}
