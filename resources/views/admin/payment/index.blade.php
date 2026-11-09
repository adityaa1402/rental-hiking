@extends('layouts.app')

@section('content')
<!-- Header Banner -->
<div class="py-4" style="background-color: var(--forest-green); color: #ffffff;">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-1">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-white-50 text-decoration-none">Admin</a></li>
                <li class="breadcrumb-item active text-white" aria-current="page">Verifikasi Pembayaran</li>
            </ol>
        </nav>
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="fw-bold mb-0">Verifikasi Pembayaran</h2>
                <p class="text-white-50 small mb-0">Kelola pembayaran pelanggan yang menunggu verifikasi</p>
            </div>
            <a href="{{ route('admin.payment.history') }}" class="btn btn-light btn-sm px-3">
                <i class="bi bi-clock-history me-1"></i> Riwayat Pembayaran
            </a>
        </div>
    </div>
</div>

<main class="py-5">
    <div class="container">
        <!-- Info Card -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-3 p-3" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                    <div class="d-flex align-items-center">
                        <div style="font-size: 2rem;"><i class="bi bi-hourglass-split"></i></div>
                        <div class="ms-3">
                            <small>Menunggu Verifikasi</small>
                            <h4 class="mb-0 fw-bold">{{ $pembayarans->total() }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong><i class="bi bi-exclamation-circle me-2"></i>Terdapat Kesalahan:</strong>
                <ul class="mb-0 mt-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Search & Filter -->
        <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
            <form method="GET" class="row g-2">
                <div class="col-lg-8">
                    <input type="text" name="q" class="form-control form-control-lg" placeholder="Cari kode transaksi atau nama pelanggan..." value="{{ request('q') }}">
                </div>
                <div class="col-lg-4">
                    <button type="submit" class="btn btn-primary btn-lg w-100">
                        <i class="bi bi-search me-1"></i> Cari
                    </button>
                </div>
            </form>
        </div>

        <!-- Pembayaran List -->
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
                                <th>Upload</th>
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
                                        <span class="badge bg-light text-dark">{{ formatRupiah($pembayaran->jumlah) }}</span>
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ $pembayaran->metode_pembayaran }}</small>
                                    </td>
                                    <td>
                                        @if($pembayaran->bukti_pembayaran)
                                            <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i> Ada</span>
                                        @else
                                            <span class="badge bg-warning text-dark"><i class="bi bi-exclamation-circle me-1"></i> Belum</span>
                                        @endif
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
                <p class="text-muted mt-3 mb-0">Tidak ada pembayaran yang menunggu verifikasi</p>
            </div>
        @endif
    </div>
</main>
@endsection
