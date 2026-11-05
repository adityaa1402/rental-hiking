@extends('layouts.admin')

@section('title', 'Manajemen Kategori')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h4 class="fw-bold mb-1">Manajemen Kategori Perlengkapan</h4>
        <small class="text-muted">Kelola kategori perlengkapan hiking untuk pengorganisasian barang.</small>
    </div>
    <a href="{{ route('admin.kategori.create') }}" class="btn btn-success btn-sm">
        <i class="bi bi-plus-circle me-1"></i> Tambah Kategori
    </a>
</div>

<div class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light small text-muted">
                <tr>
                    <th class="ps-4">No</th>
                    <th>Nama Kategori</th>
                    <th>Jumlah Barang</th>
                    <th class="text-end pe-4">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($kategoriList as $idx => $kategori)
                    <tr>
                        <td class="ps-4 text-muted small">{{ $idx + 1 }}</td>
                        <td>
                            <div class="fw-bold">{{ $kategori->nama_kategori }}</div>
                            <small class="text-muted">{{ $kategori->deskripsi }}</small>
                        </td>
                        <td>
                            <span class="badge bg-info">{{ $kategori->alat_hiking_count }} barang</span>
                        </td>
                        <td class="text-end pe-4">
                            <a href="{{ route('admin.kategori.edit', $kategori->id_kategori) }}" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-pencil me-1"></i> Edit
                            </a>
                            <form method="POST" action="{{ route('admin.kategori.destroy', $kategori->id_kategori) }}" 
                                  style="display:inline;" onsubmit="return confirm('Hapus kategori ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm">
                                    <i class="bi bi-trash me-1"></i> Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center py-5 text-muted">Belum ada kategori. <a href="{{ route('admin.kategori.create') }}">Buat sekarang</a></td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
