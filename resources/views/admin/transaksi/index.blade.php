@extends('layouts.admin')

@section('title', 'Manajemen Transaksi')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h4 class="fw-bold mb-1">Manajemen Transaksi Penyewaan</h4>
        <small class="text-muted">Kelola reservasi sewa, verifikasi pembayaran, persetujuan booking, dan tracking status.</small>
    </div>
</div>

<!-- Card Filter & Pencarian -->
<div class="card border-0 shadow-sm rounded-4 p-3 bg-white mb-4">
    <form method="GET" class="row g-2 align-items-center">
        <div class="col-md-3">
            <input type="text" name="q" class="form-control form-control-sm" placeholder="Kode transaksi / Nama pelanggan..." value="{{ request('q', '') }}">
        </div>
        <div class="col-md-2">
            <select name="status" class="form-select form-select-sm">
                <option value="">Semua Status</option>
                <option value="Pending" {{ request('status') === 'Pending' ? 'selected' : '' }}>Pending</option>
                <option value="Disetujui" {{ request('status') === 'Disetujui' ? 'selected' : '' }}>Disetujui</option>
                <option value="Berjalan" {{ request('status') === 'Berjalan' ? 'selected' : '' }}>Berjalan (Disewa)</option>
                <option value="Selesai" {{ request('status') === 'Selesai' ? 'selected' : '' }}>Selesai</option>
                <option value="Ditolak" {{ request('status') === 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
            </select>
        </div>
        <div class="col-md-2">
            <input type="date" name="start_date" class="form-control form-control-sm" value="{{ request('start_date', '') }}">
        </div>
        <div class="col-md-2">
            <input type="date" name="end_date" class="form-control form-control-sm" value="{{ request('end_date', '') }}">
        </div>
        <div class="col-md-3 d-flex gap-1">
            <button type="submit" class="btn btn-outline-primary btn-sm flex-fill">Terapkan Filter</button>
            <a href="{{ route('admin.transaksi.index') }}" class="btn btn-light btn-sm border">Reset</a>
        </div>
    </form>
</div>

<!-- Tabel Transaksi -->
<div class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light small text-muted">
                <tr>
                    <th class="ps-4">No</th>
                    <th>Kode Transaksi</th>
                    <th>Pelanggan</th>
                    <th>Periode Sewa</th>
                    <th>Total Biaya</th>
                    <th>Status</th>
                    <th class="text-end pe-4">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transaksis as $idx => $tx)
                    <tr>
                        <td class="ps-4 text-muted small">{{ $idx + 1 }}</td>
                        <td>
                            <strong class="font-monospace text-dark">{{ $tx->id_transaksi }}</strong>
                            <div class="small text-muted">{{ \Carbon\Carbon::parse($tx->created_at)->format('d/m/Y H:i') }}</div>
                        </td>
                        <td>
                            <div class="fw-bold">{{ $tx->user->nama_lengkap ?? 'N/A' }}</div>
                            <small class="text-muted">{{ $tx->user->email ?? '' }}</small>
                        </td>
                        <td class="small">
                            <div>{{ \Carbon\Carbon::parse($tx->tgl_sewa)->format('d/m/Y') }} → {{ \Carbon\Carbon::parse($tx->tgl_kembali)->format('d/m/Y') }}</div>
                            <span class="badge bg-light text-dark border">{{ $tx->total_hari }} Hari</span>
                        </td>
                        <td class="font-monospace fw-bold text-success">
                            {{ formatRupiah($tx->total_harga) }}
                        </td>
                        <td>
                            @php
                                $statusClass = match($tx->status_transaksi) {
                                    'Pending' => 'warning',
                                    'Disetujui' => 'info',
                                    'Berjalan' => 'primary',
                                    'Selesai' => 'success',
                                    'Ditolak' => 'danger',
                                    default => 'secondary'
                                };
                            @endphp
                            <span class="badge bg-{{ $statusClass }}">{{ $tx->status_transaksi }}</span>
                        </td>
                        <td class="text-end pe-4">
                            <a href="{{ route('admin.transaksi.show', $tx->id_transaksi) }}" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-eye me-1"></i> Detail
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">Tidak ada transaksi yang ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Pagination -->
@if($transaksis->hasPages())
    <div class="d-flex justify-content-center mt-4">
        {{ $transaksis->links('pagination::bootstrap-5') }}
    </div>
@endif

@endsection
