<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Transaksi extends Model
{
    protected $table = 'transaksi';
    protected $primaryKey = 'id_transaksi';
    public $timestamps = false;

    const CREATED_AT = 'created_at';

    protected $fillable = [
        'kode_transaksi', 'id_user', 'tgl_sewa', 'tgl_kembali', 'total_hari',
        'subtotal', 'biaya_pengiriman', 'diskon', 'denda', 'total_harga',
        'metode_pengambilan', 'alamat_pengiriman', 'status_transaksi',
        'catatan_admin', 'catatan_customer',
    ];

    protected $casts = [
        'tgl_sewa'    => 'date',
        'tgl_kembali' => 'date',
        'created_at'  => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function detailTransaksi(): HasMany
    {
        return $this->hasMany(DetailTransaksi::class, 'id_transaksi', 'id_transaksi');
    }

    public function pengembalian(): HasOne
    {
        return $this->hasOne(Pengembalian::class, 'id_transaksi', 'id_transaksi');
    }

    public function dokumenJaminan(): HasMany
    {
        return $this->hasMany(DokumenJaminan::class, 'id_transaksi', 'id_transaksi');
    }

    /**
     * Generate kode transaksi unik: HKT-YYYYMMDD-XXX
     */
    public static function generateKode(): string
    {
        $datePart = now()->format('Ymd');
        $prefix = "HKT-{$datePart}-";
        $count = self::where('kode_transaksi', 'like', $prefix . '%')->count() + 1;
        return sprintf('%s%03d', $prefix, $count);
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status_transaksi) {
            'Pending'    => '<span class="badge bg-warning text-dark px-3 py-2"><i class="bi bi-clock-history me-1"></i> Menunggu Persetujuan</span>',
            'Disetujui'  => '<span class="badge bg-info text-dark px-3 py-2"><i class="bi bi-check2-circle me-1"></i> Disetujui</span>',
            'Berjalan'   => '<span class="badge bg-primary px-3 py-2"><i class="bi bi-arrow-repeat me-1"></i> Sedang Disewa</span>',
            'Selesai'    => '<span class="badge bg-success px-3 py-2"><i class="bi bi-check-all me-1"></i> Selesai</span>',
            'Ditolak'    => '<span class="badge bg-danger px-3 py-2"><i class="bi bi-x-circle me-1"></i> Ditolak</span>',
            'Dibatalkan' => '<span class="badge bg-secondary px-3 py-2"><i class="bi bi-slash-circle me-1"></i> Dibatalkan</span>',
            default      => '<span class="badge bg-secondary px-3 py-2">' . e($this->status_transaksi) . '</span>',
        };
    }
}

