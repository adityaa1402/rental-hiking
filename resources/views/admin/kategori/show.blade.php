@extends('layouts.admin')

@section('title', 'Detail Kategori')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h4 class="fw-bold mb-1">{{ $kategori->nama_kategori }}</h4>
        <small class="text-muted">{{ $kategori->alatHiking->count() }} barang dalam kategori ini</small>
    </div>
    <a href="{{ route('admin.kategori.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Kembali
    </a>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
            <h6 class="fw-bold mb-3 border-bottom pb-2">Informasi Kategori</h6>
            <div class="row g-2 small">
                <div class="col-12">
                    <div class="text-muted d-block mb-1">Deskripsi</div>
                    <div>{{ $kategori->deskripsi ?? '-' }}</div>
                </div>
                <div class="col-12">
                    <div class="text-muted d-block mb-1">Jumlah Barang</div>
                    <div class="fw-bold">{{ $kategori->alatHiking->count() }} Barang</div>
                </div>
            </div>

            <div class="d-flex gap-2 mt-3">
                <a href="{{ route('admin.kategori.edit', $kategori->id_kategori) }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-pencil me-1"></i> Edit
                </a>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
            <h6 class="fw-bold mb-3 border-bottom pb-2">Barang dalam Kategori Ini</h6>

            @if($kategori->alatHiking->count() > 0)
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead class="table-light small">
                            <tr>
                                <th>Nama Barang</th>
                                <th>Merk</th>
                                <th>Stok</th>
                                <th class="text-end">Harga/Hari</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($kategori->alatHiking as $alat)
                                <tr>
                                    <td class="fw-bold">{{ $alat->nama_barang }}</td>
                                    <td>{{ $alat->merk ?? '-' }}</td>
                                    <td>
                                        <span class="badge bg-info">{{ $alat->stok_total }}</span>
                                    </td>
                                    <td class="text-end">{{ formatRupiah($alat->harga_sewa_perhari) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info small mb-0">
                    <i class="bi bi-info-circle me-2"></i> Tidak ada barang dalam kategori ini.
                </div>
            @endif
        </div>
    </div>
</div>

@endsection
