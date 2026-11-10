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

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light small text-muted">
                <tr>
                    <th class="ps-4">No</th>
                    <th>Foto</th>
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
                        <td class="ps-4 text-muted small">{{ $paketList->firstItem() + $idx }}</td>
                        <td>
                            <img src="{{ getPackageImageUrl($paket) }}" alt="{{ $paket->nama_paket }}"
                                class="rounded-3 border bg-light p-1" style="width:76px;height:56px;object-fit:contain;">
                        </td>
                        <td>
                            <div class="fw-bold">{{ $paket->nama_paket }}</div>
                            <small class="text-muted">{{ Str::limit($paket->deskripsi, 50) }}</small>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark">{{ $paket->kapasitas_orang }} Orang</span>
                        </td>
                        <td class="fw-bold text-success">{{ formatRupiah($paket->harga_paket_per_hari) }}</td>
                        <td>
                            <span class="badge bg-info">{{ $paket->detail_paket_count }} Item</span>
                        </td>
                        <td>
                            <span class="badge bg-{{ $paket->status_aktif ? 'success' : 'secondary' }}">
                                {{ $paket->status_aktif ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                        <td class="text-end pe-4">
                            <div class="d-flex justify-content-end align-items-center gap-2 flex-nowrap">
                                <a href="{{ route('admin.paket.edit', $paket->id_paket) }}"
                                   class="btn btn-outline-primary btn-sm rounded-3 d-inline-flex align-items-center justify-content-center"
                                   style="width:36px;height:36px;" title="Edit paket" aria-label="Edit paket {{ $paket->nama_paket }}">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.paket.destroy', $paket->id_paket) }}"
                                      class="d-inline-flex m-0" onsubmit="return confirm('Hapus paket ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="btn btn-outline-danger btn-sm rounded-3 d-inline-flex align-items-center justify-content-center"
                                            style="width:36px;height:36px;" title="Hapus paket"
                                            aria-label="Hapus paket {{ $paket->nama_paket }}">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">Belum ada paket. <a href="{{ route('admin.paket.create') }}">Buat sekarang</a></td>
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
