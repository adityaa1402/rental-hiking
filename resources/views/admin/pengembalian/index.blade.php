@extends('layouts.admin')

@section('title', 'Manajemen Pengembalian')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h4 class="fw-bold mb-1">Manajemen Pengembalian Barang</h4>
        <small class="text-muted">Proses pengembalian barang, verifikasi kondisi, dan hitung denda.</small>
    </div>
</div>

<!-- Filter -->
<div class="card border-0 shadow-sm rounded-4 p-3 bg-white mb-4">
    <form method="GET" class="row g-2 align-items-center">
        <div class="col-md-3">
            <input type="text" name="q" class="form-control form-control-sm" placeholder="Cari kode transaksi..." value="{{ request('q', '') }}">
        </div>
        <div class="col-md-3">
            <select name="status" class="form-select form-select-sm">
                <option value="">Semua Status</option>
                <option value="Berjalan" {{ request('status') === 'Berjalan' ? 'selected' : '' }}>Sedang Disewa</option>
                <option value="Selesai" {{ request('status') === 'Selesai' ? 'selected' : '' }}>Sudah Kembali</option>
            </select>
        </div>
        <div class="col-md-6 d-flex gap-1">
            <button type="submit" class="btn btn-outline-primary btn-sm flex-fill">Cari</button>
            <a href="{{ route('admin.pengembalian.index') }}" class="btn btn-light btn-sm border">Reset</a>
        </div>
    </form>
</div>

<!-- Tabel -->
<div class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light small text-muted">
                <tr>
                    <th class="ps-4">No</th>
                    <th>Kode Transaksi</th>
                    <th>Pelanggan</th>
                    <th>Periode Sewa</th>
                    <th>Status</th>
                    <th class="text-end pe-4">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transaksiList as $idx => $tx)
                    <tr>
                        <td class="ps-4 text-muted small">{{ $idx + 1 }}</td>
                        <td class="font-monospace fw-bold">{{ $tx->id_transaksi }}</td>
                        <td>{{ $tx->user->nama_lengkap ?? '-' }}</td>
                        <td class="small">{{ \Carbon\Carbon::parse($tx->tgl_sewa)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($tx->tgl_kembali)->format('d/m/Y') }}</td>
                        <td>
                            @php
                                $statusClass = $tx->status_transaksi === 'Berjalan' ? 'primary' : 'success';
                            @endphp
                            <span class="badge bg-{{ $statusClass }}">{{ $tx->status_transaksi }}</span>
                        </td>
                        <td class="text-end pe-4">
                            <a href="{{ route('admin.pengembalian.show', $tx->id_transaksi) }}" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-box-arrow-in-left me-1"></i> Proses
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">Tidak ada transaksi untuk diproses pengembalian.</td>
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
