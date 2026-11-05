@extends('layouts.admin')

@section('title', 'Manajemen Customer')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h4 class="fw-bold mb-1">Manajemen Pelanggan</h4>
        <small class="text-muted">Lihat daftar pelanggan dan riwayat transaksi mereka.</small>
    </div>
</div>

<!-- Filter -->
<div class="card border-0 shadow-sm rounded-4 p-3 bg-white mb-4">
    <form method="GET" class="row g-2 align-items-center">
        <div class="col-md-4">
            <input type="text" name="q" class="form-control form-control-sm" placeholder="Cari nama atau email..." value="{{ request('q', '') }}">
        </div>
        <div class="col-md-8 d-flex gap-1">
            <button type="submit" class="btn btn-outline-primary btn-sm">Cari</button>
            <a href="{{ route('admin.customer.index') }}" class="btn btn-light btn-sm border">Reset</a>
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
                    <th>Nama Pelanggan</th>
                    <th>Email</th>
                    <th class="text-center">Total Transaksi</th>
                    <th class="text-end pe-4">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($customerList as $idx => $customer)
                    <tr>
                        <td class="ps-4 text-muted small">{{ $idx + 1 }}</td>
                        <td>
                            <div class="fw-bold">{{ $customer->nama_lengkap }}</div>
                            <small class="text-muted">{{ $customer->no_hp ?? '-' }}</small>
                        </td>
                        <td class="text-break">{{ $customer->email }}</td>
                        <td class="text-center">
                            <span class="badge bg-info">{{ $customer->transaksi_count }}</span>
                        </td>
                        <td class="text-end pe-4">
                            <a href="{{ route('admin.customer.show', $customer->id_user) }}" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-eye me-1"></i> Detail
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">Tidak ada pelanggan ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Pagination -->
@if($customerList->hasPages())
    <div class="d-flex justify-content-center mt-4">
        {{ $customerList->links('pagination::bootstrap-5') }}
    </div>
@endif

@endsection
