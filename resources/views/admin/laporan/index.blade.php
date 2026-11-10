@extends('layouts.admin')

@section('title', 'Laporan Transaksi')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h4 class="fw-bold mb-1">Laporan Transaksi</h4>
        <small class="text-muted">Laporan penyewaan dan pendapatan toko.</small>
    </div>
    <a href="{{ route('admin.laporan.cetak') }}" target="_blank" class="btn btn-outline-success btn-sm">
        <i class="bi bi-printer me-1"></i> Cetak
    </a>
</div>

<!-- Filter -->
<div class="card border-0 shadow-sm rounded-4 p-3 bg-white mb-4">
    <form method="GET" class="row g-2 align-items-center">
        <div class="col-md-2">
            <input type="date" name="dari" class="form-control form-control-sm" value="{{ request('dari', '') }}">
        </div>
        <div class="col-md-2">
            <input type="date" name="sampai" class="form-control form-control-sm" value="{{ request('sampai', '') }}">
        </div>
        <div class="col-md-3">
            <select name="status" class="form-select form-select-sm">
                <option value="">Semua Status</option>
                <option value="Disetujui" {{ request('status') === 'Disetujui' ? 'selected' : '' }}>Disetujui</option>
                <option value="Berjalan" {{ request('status') === 'Berjalan' ? 'selected' : '' }}>Berjalan</option>
                <option value="Selesai" {{ request('status') === 'Selesai' ? 'selected' : '' }}>Selesai</option>
            </select>
        </div>
        <div class="col-md-5 d-flex gap-1">
            <button type="submit" class="btn btn-outline-primary btn-sm flex-fill">Filter</button>
            <a href="{{ route('admin.laporan.index') }}" class="btn btn-light btn-sm border">Reset</a>
        </div>
    </form>
</div>

<!-- Ringkasan -->
<div class="row mb-4 g-2">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
            <div class="text-muted small mb-2">Total Pendapatan</div>
            <div class="fw-bold text-success" style="font-size:1.8rem;">
                {{ formatRupiah($totalPendapatan) }}
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
            <div class="text-muted small mb-2">Total Transaksi</div>
            <div class="fw-bold text-primary" style="font-size:1.8rem;">
                {{ $transaksiList->total() }} Transaksi
            </div>
        </div>
    </div>
</div>

<!-- Tabel -->
<div class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0 small">
            <thead class="table-light text-muted">
                <tr>
                    <th class="ps-4">No</th>
                    <th>Kode Transaksi</th>
                    <th>Pelanggan</th>
                    <th>Periode</th>
                    <th class="text-end">Total</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transaksiList as $idx => $tx)
                    <tr>
                        <td class="ps-4 text-muted">{{ $transaksiList->firstItem() + $idx }}</td>
                        <td class="font-monospace fw-bold">{{ $tx->kode_transaksi }}</td>
                        <td>{{ $tx->user->nama_lengkap ?? '-' }}</td>
                        <td class="small">{{ \Carbon\Carbon::parse($tx->tgl_sewa)->format('d/m') }} - {{ \Carbon\Carbon::parse($tx->tgl_kembali)->format('d/m/Y') }}</td>
                        <td class="text-end fw-bold text-success">{{ formatRupiah($tx->total_harga) }}</td>
                        <td>
                            @php
                                $statusClass = match($tx->status_transaksi) {
                                    'Disetujui' => 'info',
                                    'Berjalan' => 'primary',
                                    'Selesai' => 'success',
                                    default => 'secondary'
                                };
                            @endphp
                            <span class="badge bg-{{ $statusClass }} small">{{ $tx->status_transaksi }}</span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">Tidak ada data laporan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Pagination -->
@if($transaksiList->hasPages())
    <div class="d-flex justify-content-center mt-4">
        {{ $transaksiList->links('pagination::bootstrap-5') }}
    </div>
@endif

@endsection
