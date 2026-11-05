@extends('layouts.app')

@section('title', 'Dashboard Saya')

@push('styles')
<style>
.customer-header {
    background: linear-gradient(135deg, #173F35, #2d6a4f);
    padding: 2.5rem 0;
    color: white;
}
.stat-card {
    border: none;
    border-radius: 16px;
    padding: 1.5rem;
    display: flex;
    align-items: center;
    gap: 1.25rem;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    transition: all 0.3s;
}
.stat-card:hover { transform: translateY(-3px); box-shadow: 0 10px 30px rgba(0,0,0,0.12); }
.stat-icon {
    width: 60px; height: 60px; border-radius: 14px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.75rem; flex-shrink: 0;
}
.stat-num { font-size: 2rem; font-weight: 800; line-height: 1; }
.stat-label { color: #6c757d; font-size: 0.85rem; }
.section-title { font-size: 1.1rem; font-weight: 700; color: #173F35; }
.transaksi-row:hover { background: #f8faf9; }
.status-badge { font-size: 0.75rem; padding: 0.3rem 0.8rem; border-radius: 50px; font-weight: 600; }
</style>
@endpush

@section('content')

<div class="customer-header">
    <div class="container">
        <div class="d-flex align-items-center gap-4">
            <div class="rounded-circle bg-white bg-opacity-20 d-flex align-items-center justify-content-center flex-shrink-0"
                style="width:70px;height:70px;font-size:2rem;">
                <i class="bi bi-person-circle text-warning"></i>
            </div>
            <div>
                <div class="text-warning small fw-semibold mb-1"><i class="bi bi-patch-check me-1"></i>Customer</div>
                <h2 class="fw-bold mb-0">Halo, {{ auth()->user()->nama_lengkap }}!</h2>
                <p class="text-white-50 mb-0 small">{{ auth()->user()->email }}</p>
            </div>
            <div class="ms-auto d-none d-md-block">
                <a href="{{ route('booking.create') }}" class="btn btn-warning fw-bold px-4 py-2">
                    <i class="bi bi-bag-plus me-2"></i>Sewa Alat Baru
                </a>
            </div>
        </div>
    </div>
</div>

<div class="py-5" style="background:#f8faf9;">
    <div class="container">

        {{-- Stats --}}
        <div class="row g-3 mb-5">
            <div class="col-md-3 col-6">
                <div class="stat-card bg-white">
                    <div class="stat-icon" style="background:#e8f5e9;color:#173F35;">
                        <i class="bi bi-receipt-cutoff"></i>
                    </div>
                    <div>
                        <div class="stat-num" style="color:#173F35;">{{ $stats['total'] }}</div>
                        <div class="stat-label">Total Transaksi</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-card bg-white">
                    <div class="stat-icon" style="background:#fff3cd;color:#856404;">
                        <i class="bi bi-clock-history"></i>
                    </div>
                    <div>
                        <div class="stat-num" style="color:#856404;">{{ $stats['pending'] }}</div>
                        <div class="stat-label">Menunggu</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-card bg-white">
                    <div class="stat-icon" style="background:#cce5ff;color:#004085;">
                        <i class="bi bi-bag-check"></i>
                    </div>
                    <div>
                        <div class="stat-num" style="color:#004085;">{{ $stats['berjalan'] }}</div>
                        <div class="stat-label">Berjalan</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-card bg-white">
                    <div class="stat-icon" style="background:#d4edda;color:#155724;">
                        <i class="bi bi-check-circle"></i>
                    </div>
                    <div>
                        <div class="stat-num" style="color:#155724;">{{ $stats['selesai'] }}</div>
                        <div class="stat-label">Selesai</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            {{-- Recent Transactions --}}
            <div class="col-lg-8">
                <div class="card border-0 rounded-4 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="section-title"><i class="bi bi-clock-history me-2"></i>Transaksi Terbaru</div>
                            <a href="{{ route('customer.transaksi.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill small">
                                Lihat Semua <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>

                        @if($recentTransaksis->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="small text-muted">
                                    <tr>
                                        <th>Kode</th>
                                        <th>Tanggal Sewa</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentTransaksis as $trx)
                                    <tr class="transaksi-row">
                                        <td>
                                            <div class="fw-bold small" style="color:#173F35;">{{ $trx->kode_transaksi }}</div>
                                            <div class="text-muted" style="font-size:0.72rem;">{{ $trx->created_at->format('d/m/Y') }}</div>
                                        </td>
                                        <td class="small">
                                            {{ \Carbon\Carbon::parse($trx->tgl_sewa)->format('d M Y') }}
                                            <div class="text-muted" style="font-size:0.72rem;">s/d {{ \Carbon\Carbon::parse($trx->tgl_kembali)->format('d M Y') }}</div>
                                        </td>
                                        <td class="fw-semibold small" style="color:#173F35;">
                                            Rp {{ number_format($trx->total_harga, 0, ',', '.') }}
                                        </td>
                                        <td>{!! $trx->status_badge !!}</td>
                                        <td>
                                            <a href="{{ route('customer.transaksi.show', $trx->id_transaksi) }}"
                                                class="btn btn-sm btn-outline-secondary rounded-pill" style="font-size:0.75rem;">
                                                Detail
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="text-center py-4">
                            <i class="bi bi-receipt text-muted mb-3 d-block" style="font-size:3rem;"></i>
                            <p class="text-muted mb-3">Belum ada transaksi.</p>
                            <a href="{{ route('booking.create') }}" class="btn btn-brand px-4">
                                <i class="bi bi-bag-plus me-1"></i> Mulai Sewa Sekarang
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="col-lg-4">
                {{-- Profile Card --}}
                <div class="card border-0 rounded-4 shadow-sm mb-3">
                    <div class="card-body p-4">
                        <div class="section-title mb-3"><i class="bi bi-person-circle me-2"></i>Profil Saya</div>
                        <div class="d-flex flex-column gap-2 small">
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-person text-muted" style="width:16px;"></i>
                                <span>{{ auth()->user()->nama_lengkap }}</span>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-envelope text-muted" style="width:16px;"></i>
                                <span class="text-muted">{{ auth()->user()->email }}</span>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-telephone text-muted" style="width:16px;"></i>
                                <span class="text-muted">{{ auth()->user()->no_hp ?? '-' }}</span>
                            </div>
                        </div>
                        <a href="{{ route('customer.profil.index') }}" class="btn btn-outline-secondary btn-sm w-100 mt-3 rounded-3">
                            <i class="bi bi-pencil me-1"></i> Edit Profil
                        </a>
                    </div>
                </div>

                {{-- Quick Links --}}
                <div class="card border-0 rounded-4 shadow-sm">
                    <div class="card-body p-4">
                        <div class="section-title mb-3"><i class="bi bi-lightning-charge me-2"></i>Aksi Cepat</div>
                        <div class="d-flex flex-column gap-2">
                            <a href="{{ route('booking.create') }}" class="btn btn-brand w-100 py-2">
                                <i class="bi bi-bag-plus me-2"></i> Sewa Alat Baru
                            </a>
                            <a href="{{ route('katalog.index') }}" class="btn btn-outline-secondary w-100 py-2">
                                <i class="bi bi-grid me-2"></i> Lihat Katalog
                            </a>
                            <a href="{{ route('customer.transaksi.index') }}" class="btn btn-outline-secondary w-100 py-2">
                                <i class="bi bi-receipt-cutoff me-2"></i> Riwayat Sewa
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

