@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@push('styles')
<style>
.stat-card {
    border: none;
    border-radius: 16px;
    overflow: hidden;
    transition: all 0.3s;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
}
.stat-card:hover { transform: translateY(-3px); box-shadow: 0 10px 30px rgba(0,0,0,0.12); }
.stat-card .card-body {
    padding: 1.5rem;
    display: flex;
    align-items: center;
    gap: 1.25rem;
}
.stat-icon {
    width: 60px; height: 60px; border-radius: 14px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.75rem; flex-shrink: 0;
}
.stat-num { font-size: 2rem; font-weight: 800; line-height: 1; color: #173F35; }
.stat-label { color: #6c757d; font-size: 0.82rem; font-weight: 500; }
.transaksi-row:hover { background: #f8faf9; }
</style>
@endpush

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0" style="color:#173F35;">
            <i class="bi bi-speedometer2 me-2"></i>Overview Dashboard
        </h4>
        <div class="text-muted small">{{ now()->format('l, d F Y') }}</div>
    </div>
    <a href="{{ route('admin.transaksi.index') }}" class="btn btn-brand btn-sm px-3">
        <i class="bi bi-receipt-cutoff me-1"></i> Kelola Transaksi
    </a>
</div>

{{-- Stat Cards --}}
<div class="row g-3 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="stat-card card">
            <div class="card-body">
                <div class="stat-icon" style="background:#e8f5e9;color:#173F35;">
                    <i class="bi bi-box-seam"></i>
                </div>
                <div>
                    <div class="stat-num">{{ $stats['total_barang'] }}</div>
                    <div class="stat-label">Total Barang</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card card">
            <div class="card-body">
                <div class="stat-icon" style="background:#fff3cd;color:#856404;">
                    <i class="bi bi-clock-history"></i>
                </div>
                <div>
                    <div class="stat-num" style="color:#856404;">{{ $stats['transaksi_pending'] }}</div>
                    <div class="stat-label">Transaksi Pending</div>
                    @if($stats['transaksi_pending'] > 0)
                    <div><a href="{{ route('admin.transaksi.index', ['status'=>'Pending']) }}" class="text-warning small fw-semibold">Lihat →</a></div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card card">
            <div class="card-body">
                <div class="stat-icon" style="background:#cce5ff;color:#004085;">
                    <i class="bi bi-bag-check"></i>
                </div>
                <div>
                    <div class="stat-num" style="color:#004085;">{{ $stats['transaksi_berjalan'] }}</div>
                    <div class="stat-label">Sedang Berjalan</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card card">
            <div class="card-body">
                <div class="stat-icon" style="background:#d4edda;color:#155724;">
                    <i class="bi bi-people"></i>
                </div>
                <div>
                    <div class="stat-num" style="color:#155724;">{{ $stats['total_customer'] }}</div>
                    <div class="stat-label">Total Customer</div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Revenue Row --}}
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card border-0 rounded-4 shadow-sm p-4" style="background:linear-gradient(135deg,#173F35,#2d6a4f);">
            <div class="text-warning small fw-semibold mb-1"><i class="bi bi-calendar-month me-1"></i>Pendapatan Bulan Ini</div>
            <div class="text-white fw-bold" style="font-size:1.6rem;">
                Rp {{ number_format($stats['pendapatan_bulan'], 0, ',', '.') }}
            </div>
            <div class="text-white-50 small">{{ now()->format('F Y') }}</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 rounded-4 shadow-sm p-4">
            <div class="text-muted small fw-semibold mb-1"><i class="bi bi-graph-up me-1 text-success"></i>Total Pendapatan</div>
            <div class="fw-bold" style="font-size:1.6rem;color:#173F35;">
                Rp {{ number_format($stats['total_pendapatan'], 0, ',', '.') }}
            </div>
            <div class="text-muted small">Semua waktu</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 rounded-4 shadow-sm p-4">
            <div class="text-muted small fw-semibold mb-1"><i class="bi bi-check-circle me-1 text-success"></i>Transaksi Selesai</div>
            <div class="fw-bold" style="font-size:1.6rem;color:#173F35;">
                {{ $stats['transaksi_selesai'] }}
            </div>
            <div class="text-muted small">Transaksi sukses</div>
        </div>
    </div>
</div>

{{-- Recent Transactions --}}
<div class="card border-0 rounded-4 shadow-sm">
    <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="fw-bold" style="color:#173F35;font-size:1rem;">
                <i class="bi bi-receipt-cutoff me-2"></i>Transaksi Terbaru
            </div>
            <a href="{{ route('admin.transaksi.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill">
                Semua Transaksi <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>

        @if($recentTransaksis->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead style="background:#f8faf9;" class="small text-muted">
                    <tr>
                        <th>Kode</th>
                        <th>Customer</th>
                        <th>Tanggal</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentTransaksis as $trx)
                    <tr class="transaksi-row">
                        <td>
                            <div class="fw-bold small" style="color:#173F35;">{{ $trx->kode_transaksi }}</div>
                            <div class="text-muted" style="font-size:0.72rem;">{{ $trx->created_at->format('d/m/Y H:i') }}</div>
                        </td>
                        <td>
                            <div class="fw-semibold small">{{ $trx->user->nama_lengkap ?? '-' }}</div>
                            <div class="text-muted" style="font-size:0.72rem;">{{ $trx->user->email ?? '' }}</div>
                        </td>
                        <td class="small">
                            {{ \Carbon\Carbon::parse($trx->tgl_sewa)->format('d M') }}
                            → {{ \Carbon\Carbon::parse($trx->tgl_kembali)->format('d M Y') }}
                            <div class="text-muted" style="font-size:0.72rem;">{{ $trx->total_hari }} hari</div>
                        </td>
                        <td class="fw-semibold small" style="color:#173F35;">
                            Rp {{ number_format($trx->total_harga, 0, ',', '.') }}
                        </td>
                        <td>{!! $trx->status_badge !!}</td>
                        <td class="text-center">
                            <a href="{{ route('admin.transaksi.show', $trx->id_transaksi) }}"
                                class="btn btn-sm btn-outline-primary rounded-pill" style="font-size:0.75rem;">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-4 text-muted">
            <i class="bi bi-receipt fs-1 d-block mb-2"></i>
            Belum ada transaksi.
        </div>
        @endif
    </div>
</div>

@endsection

