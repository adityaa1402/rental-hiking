@extends('layouts.admin')

@section('title', $barang->nama_alat)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1" style="color:#173F35;">Detail Barang</h4>
        <small class="text-muted">Informasi lengkap perlengkapan hiking.</small>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.barang.edit', $barang->id_alat) }}" class="btn btn-warning">
            <i class="bi bi-pencil me-1"></i> Edit
        </a>
        <a href="{{ route('admin.barang.index') }}" class="btn btn-light border">Kembali</a>
    </div>
</div>

<div class="row g-4">
    <!-- Foto & Info Utama -->
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white mb-4">
            @if($barang->gambar)
                <img src="{{ getProductImageUrl($barang) }}" alt="{{ $barang->nama_alat }}" class="card-img-top" style="height:400px;object-fit:cover;">
            @else
                <div class="d-flex align-items-center justify-content-center bg-light" style="height:400px;">
                    <div class="text-center">
                        <i class="bi bi-image text-muted" style="font-size:4rem;"></i>
                        <p class="text-muted small mt-2">Belum ada foto</p>
                    </div>
                </div>
            @endif
            <div class="card-body p-4">
                <h5 class="card-title fw-bold mb-1" style="color:#173F35;">{{ $barang->nama_alat }}</h5>
                <p class="text-muted small mb-3">{{ $barang->merk }}</p>
                
                <div class="mb-3">
                    <span class="badge me-2" style="background:#e8f5e9;color:#173F35;">
                        {{ $barang->kategori->nama_kategori ?? '-' }}
                    </span>
                    <span class="badge
                        @if($barang->kondisi=='Baik') bg-success
                        @elseif($barang->kondisi=='Perlu Perawatan') bg-warning text-dark
                        @elseif($barang->kondisi=='Rusak') bg-danger
                        @else bg-secondary @endif
                    ">
                        {{ $barang->kondisi }}
                    </span>
                </div>

                <div class="border-top pt-3">
                    <div class="row g-3">
                        <div class="col-6">
                            <small class="text-muted d-block">Harga/Hari</small>
                            <h6 class="fw-bold mb-0" style="color:#173F35;">Rp {{ number_format($barang->harga_per_hari, 0, ',', '.') }}</h6>
                        </div>
                        <div class="col-6">
                            <small class="text-muted d-block">Stok Total</small>
                            <h6 class="fw-bold mb-0 {{ $barang->stok_total > 0 ? '' : 'text-danger' }}" style="color:#173F35;">
                                {{ $barang->stok_total }} unit
                            </h6>
                            <small class="text-muted">{{ max(0, $barang->stok_total - $barang->stok_rusak) }} unit siap disewa</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detail Informasi -->
    <div class="col-lg-7">
        <!-- Deskripsi -->
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
            <h6 class="fw-bold mb-3 border-bottom pb-2" style="color:#173F35;">Deskripsi</h6>
            <p class="mb-0 text-muted" style="line-height:1.6;">{{ $barang->deskripsi }}</p>
        </div>

        <!-- Spesifikasi -->
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
            <h6 class="fw-bold mb-3 border-bottom pb-2" style="color:#173F35;">Spesifikasi</h6>
            <div class="text-muted small" style="white-space:pre-wrap;line-height:1.6;">{{ $barang->spesifikasi }}</div>
        </div>

        <!-- Info Teknis -->
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
            <h6 class="fw-bold mb-3 border-bottom pb-2" style="color:#173F35;">Informasi Teknis</h6>
            
            <div class="row g-3">
                <div class="col-md-6">
                    <small class="text-muted d-block mb-1">ID Barang</small>
                    <p class="fw-semibold mb-0" style="color:#173F35;">#{{ str_pad($barang->id_alat, 5, '0', STR_PAD_LEFT) }}</p>
                </div>
                <div class="col-md-6">
                    <small class="text-muted d-block mb-1">Slug</small>
                    <p class="fw-semibold mb-0 text-break" style="color:#173F35;font-size:0.9rem;">{{ $barang->slug }}</p>
                </div>
                <div class="col-md-6">
                    <small class="text-muted d-block mb-1">Dibuat Pada</small>
                    <p class="fw-semibold mb-0" style="color:#173F35;">{{ $barang->created_at->format('d M Y H:i') }}</p>
                </div>
                <div class="col-md-6">
                    <small class="text-muted d-block mb-1">Diperbarui Pada</small>
                    <p class="fw-semibold mb-0" style="color:#173F35;">{{ $barang->updated_at->format('d M Y H:i') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
