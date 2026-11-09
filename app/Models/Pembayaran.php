<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pembayaran extends Model
{
    protected $table = 'pembayaran';
    protected $primaryKey = 'id_pembayaran';
    public $timestamps = true;

    protected $fillable = [
        'id_transaksi', 'jumlah', 'metode_pembayaran', 'status_pembayaran',
        'bukti_pembayaran', 'keterangan_pembayaran', 'dikonfirmasi_oleh',
        'catatan_admin', 'tanggal_dikonfirmasi'
    ];

    protected $casts = [
        'created_at'           => 'datetime',
        'updated_at'           => 'datetime',
        'tanggal_dikonfirmasi' => 'datetime',
    ];

    /**
     * Relationship: Pembayaran belongs to Transaksi
     */
    public function transaksi(): BelongsTo
    {
        return $this->belongsTo(Transaksi::class, 'id_transaksi', 'id_transaksi');
    }

    /**
     * Relationship: Pembayaran belongs to Admin User (who confirmed)
     */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dikonfirmasi_oleh', 'id');
    }

    /**
     * Generate status badge for display
     */
    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status_pembayaran) {
            'Pending'   => '<span class="badge bg-warning text-dark px-3 py-2"><i class="bi bi-clock-history me-1"></i> Menunggu Verifikasi</span>',
            'Confirmed' => '<span class="badge bg-success px-3 py-2"><i class="bi bi-check-circle me-1"></i> Dikonfirmasi</span>',
            'Rejected'  => '<span class="badge bg-danger px-3 py-2"><i class="bi bi-x-circle me-1"></i> Ditolak</span>',
            'Refunded'  => '<span class="badge bg-info px-3 py-2"><i class="bi bi-arrow-repeat me-1"></i> Dikembalikan</span>',
            default     => '<span class="badge bg-secondary px-3 py-2">' . e($this->status_pembayaran) . '</span>',
        };
    }

    /**
     * Check if payment is confirmed
     */
    public function isConfirmed(): bool
    {
        return $this->status_pembayaran === 'Confirmed';
    }

    /**
     * Check if payment is pending
     */
    public function isPending(): bool
    {
        return $this->status_pembayaran === 'Pending';
    }

    /**
     * Check if payment is rejected
     */
    public function isRejected(): bool
    {
        return $this->status_pembayaran === 'Rejected';
    }
}
