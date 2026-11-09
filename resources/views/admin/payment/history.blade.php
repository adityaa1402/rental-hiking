@extends('layouts.app')

@section('content')
<!-- Header Banner -->
<div class="py-4" style="background-color: var(--forest-green); color: #ffffff;">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-1">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-white-50 text-decoration-none">Admin</a></li>
                <li class="breadcrumb-item active text-white" aria-current="page">Riwayat Pembayaran</li>
            </ol>
        </nav>
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="fw-bold mb-0">Riwayat Pembayaran</h2>
                <p class="text-white-50 small mb-0">Lihat historis pembayaran yang sudah diverifikasi dan ditolak</p>
            </div>
            <a href="{{ route('admin.payment.index') }}" class="btn btn-light btn-sm px-3">
                <i class="bi bi-hourglass-split me-1"></i> Pembayaran Pending
            </a>
        </div>
    </div>
</div>

<main class="py-5">
    <div class="container">
        <!-- Filter Cards -->
        <div class="row mb-4 g-3">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm rounded-3 p-3 text-center">
                    <small class="text-muted d-block">Dikonfirmasi</small>
                    <h4 class="mb-0 fw-bold text-success">{{ $pembayarans->where('status_pembayaran', 'Confirmed')->count() }}</h4>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm rounded-3 p-3 text-center">
                    <small class="text-muted d-block">Ditolak</small>
                    <h4 class="mb-0 fw-bold text-danger">{{ $pembayarans->where('status_pembayaran', 'Rejected')->count() }}</h4>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm rounded-3 p-3 text-center">
                    <small class="text-muted d-block">Dikembalikan</small>
                    <h4 class="mb-0 fw-bold text-info">{{ $pembayarans->where('status_pembayaran', 'Refunded')->count() }}</h4>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm rounded-3 p-3 text-center">
                    <small class="text-muted d-block">Total</small>
                    <h4 class="mb-0 fw-bold">{{ $pembayarans->total() }}</h4>
                </div>
            </div>
        </div>

        <!-- Search & Filter -->
        <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
            <form method="GET" class="row g-2">
                <div class="col-lg-6">
                    <input type="text" name="q" class="form-control form-control-lg" placeholder="Cari kode transaksi atau nama pelanggan..." value="{{ request('q') }}">
                </div>
                <div class="col-lg-3">
                    <select name="status" class="form-select form-select-lg">
                        <option value="">-- Semua Status --</option>
                        <option value="Confirmed" {{ request('status') == 'Confirmed' ? 'selected' : '' }}>Dikonfirmasi</option>
                        <option value="Rejected" {{ request('status') == 'Rejected' ? 'selected' : '' }}>Ditolak</option>
                        <option value="Refunded" {{ request('status') == 'Refunded' ? 'selected' : '' }}>Dikembalikan</option>
                    </select>
                </div>
                <div class="col-lg-3">
                    <button type="submit" class="btn btn-primary btn-lg w-100">
                        <i class="bi bi-search me-1"></i> Cari
                    </button>
                </div>
            </form>
        </div>

        <!-- Payment History Table -->
        @if($pembayarans->count() > 0)
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="py-3 ps-4">Kode Transaksi</th>
                                <th>Pelanggan</th>
                                <th>Jumlah</th>
                                <th>Metode</th>
                                <th>Status</th>
                                <th>Diverifikasi Oleh</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pembayarans as $pembayaran)
                                <tr class="border-bottom">
                                    <td class="py-3 ps-4">
                                        <a href="{{ route('admin.payment.show', $pembayaran->id_pembayaran) }}" class="text-decoration-none fw-bold">
                                            {{ $pembayaran->transaksi->kode_transaksi }}
                                        </a>
                                    </td>
                                    <td>
                                        <div class="fw-semibold">{{ $pembayaran->transaksi->user->nama_lengkap }}</div>
                                        <small class="text-muted">{{ $pembayaran->transaksi->user->email }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark fw-semibold">{{ formatRupiah($pembayaran->jumlah) }}</span>
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ $pembayaran->metode_pembayaran }}</small>
                                    </td>
                                    <td>
                                        @if($pembayaran->status_pembayaran === 'Confirmed')
                                            <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i> Dikonfirmasi</span>
                                        @elseif($pembayaran->status_pembayaran === 'Rejected')
                                            <span class="badge bg-danger"><i class="bi bi-x-circle me-1"></i> Ditolak</span>
                                        @elseif($pembayaran->status_pembayaran === 'Refunded')
                                            <span class="badge bg-info"><i class="bi bi-arrow-repeat me-1"></i> Dikembalikan</span>
                                        @endif
                                    </td>
                                    <td>
                                        <small>{{ $pembayaran->admin->nama_lengkap ?? '-' }}</small>
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ $pembayaran->tanggal_dikonfirmasi?->format('d M Y H:i') ?? '-' }}</small>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.payment.show', $pembayaran->id_pembayaran) }}" class="btn btn-sm btn-primary" title="Lihat Detail">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination -->
            <div class="mt-4 d-flex justify-content-center">
                {{ $pembayarans->links() }}
            </div>
        @else
            <div class="card border-0 rounded-4 p-5 text-center" style="background-color: #f8f9fa;">
                <i class="bi bi-inbox" style="font-size: 3rem; color: #adb5bd;"></i>
                <p class="text-muted mt-3 mb-0">Tidak ada riwayat pembayaran</p>
            </div>
        @endif
    </div>
</main>
@endsection
