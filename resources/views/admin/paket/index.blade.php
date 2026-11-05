@extends('layouts.admin')

@section('title', 'Manajemen Paket')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h4 class="fw-bold mb-1">Manajemen Paket Sewa</h4>
        <small class="text-muted">Kelola paket bundling perlengkapan hiking.</small>
    </div>
    <a href="{{ route('admin.paket.create') }}" class="btn btn-success btn-sm">
        <i class="bi bi-plus-circle me-1"></i> Tambah Paket
    </a>
</div>

<div class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light small text-muted">
                <tr>
                    <th class="ps-4">No</th>
                    <th>Nama Paket</th>
                    <th>Kapasitas</th>
                    <th>Harga/Hari</th>
                    <th>Jumlah Item</th>
                    <th>Status</th>
                    <th class="text-end pe-4">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($paketList as $idx => $paket)
                    <tr>
                        <td class="ps-4 text-muted small">{{ $idx + 1 }}</td>
                        <td>
                            <div class="fw-bold">{{ $paket->nama_paket }}</div>
                            <small class="text-muted">{{ Str::limit($paket->deskripsi, 50) }}</small>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark">{{ $paket->kapasitas_orang }} Orang</span>
                        </td>
                        <td class="fw-bold text-success">{{ formatRupiah($paket->harga_paket_per_hari) }}</td>
                        <td>
                            <span class="badge bg-info">{{ $paket->detailPaket->count() }} Item</span>
                        </td>
                        <td>
                            <span class="badge bg-{{ $paket->status_aktif ? 'success' : 'secondary' }}">
                                {{ $paket->status_aktif ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                        <td class="text-end pe-4">
                            <a href="{{ route('admin.paket.edit', $paket->id_paket) }}" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-pencil me-1"></i> Edit
                            </a>
                            <form method="POST" action="{{ route('admin.paket.destroy', $paket->id_paket) }}" 
                                  style="display:inline;" onsubmit="return confirm('Hapus paket ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm">
                                    <i class="bi bi-trash me-1"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">Belum ada paket. <a href="{{ route('admin.paket.create') }}">Buat sekarang</a></td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if($paketList->hasPages())
    <div class="d-flex justify-content-center mt-4">
        {{ $paketList->links('pagination::bootstrap-5') }}
    </div>
@endif

@endsection
