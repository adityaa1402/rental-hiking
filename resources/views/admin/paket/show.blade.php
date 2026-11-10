@extends('layouts.admin')

@section('title', 'Detail Paket')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h4 class="fw-bold mb-1">{{ $paket->nama_paket }}</h4>
        <small class="text-muted">{{ $paket->detailPaket->count() }} item • {{ $paket->kapasitas_orang }} orang</small>
    </div>
    <a href="{{ route('admin.paket.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Kembali
    </a>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
            <h6 class="fw-bold mb-3 border-bottom pb-2">Detail Paket</h6>
            
            <div class="row g-2 small">
                <div class="col-12">
                    <div class="text-muted d-block mb-1">Deskripsi</div>
                    <div>{{ $paket->deskripsi }}</div>
                </div>
                <div class="col-md-6">
                    <div class="text-muted d-block mb-1">Kapasitas</div>
                    <div class="fw-bold">{{ $paket->kapasitas_orang }} Orang</div>
                </div>
                <div class="col-md-6">
                    <div class="text-muted d-block mb-1">Harga/Hari</div>
                    <div class="fw-bold text-success">{{ formatRupiah($paket->harga_paket_per_hari) }}</div>
                </div>
                <div class="col-12">
                    <div class="text-muted d-block mb-1">Status</div>
                    <span class="badge bg-{{ $paket->status_aktif ? 'success' : 'secondary' }}">
                        {{ $paket->status_aktif ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </div>
            </div>

            <div class="d-flex gap-2 mt-3">
                <a href="{{ route('admin.paket.edit', $paket->id_paket) }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-pencil me-1"></i> Edit
                </a>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
            <h6 class="fw-bold mb-3 border-bottom pb-2">Item dalam Paket</h6>
            
            @if($paket->detailPaket->count() > 0)
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead class="table-light small">
                            <tr>
                                <th>Barang</th>
                                <th class="text-center">Qty</th>
                                <th class="text-end">Harga/Hari</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($paket->detailPaket as $detail)
                                <tr>
                                    <td>
                                        <div class="fw-bold">{{ $detail->alatHiking->nama_alat ?? 'N/A' }}</div>
                                        <small class="text-muted">{{ $detail->alatHiking->merk ?? '' }}</small>
                                    </td>
                                    <td class="text-center">{{ $detail->jumlah_unit }}</td>
                                    <td class="text-end">{{ formatRupiah($detail->alatHiking->harga_per_hari ?? 0) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info small mb-0">
                    <i class="bi bi-info-circle me-2"></i> Tidak ada item dalam paket ini.
                </div>
            @endif
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
            <h6 class="fw-bold mb-3 border-bottom pb-2">Gambar Paket</h6>
            @if($paket->gambar)
                <img src="{{ getPackageImageUrl($paket) }}" alt="{{ $paket->nama_paket }}" class="img-fluid rounded mb-3" style="max-height:250px;object-fit:cover;">
            @else
                <div class="alert alert-info small">Tidak ada gambar</div>
            @endif
        </div>
    </div>
</div>

@endsection
