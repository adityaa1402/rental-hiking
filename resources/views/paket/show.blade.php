@extends('layouts.app')

@section('title', $paket->nama_paket)

@section('content')

@php
    $hargaNormalPaket = $paket->detailPaket->sum(
        fn ($detail) => ($detail->alatHiking?->harga_per_hari ?? 0) * $detail->jumlah_unit
    );
    $hematPaket = max(0, $hargaNormalPaket - $paket->harga_paket_per_hari);
    $persenHematPaket = $hargaNormalPaket > 0
        ? (int) round($hematPaket / $hargaNormalPaket * 100)
        : 0;
@endphp

{{-- Breadcrumb --}}
<div class="py-3" style="background:#f8faf9;">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 small">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" style="color:#173F35;">Beranda</a></li>
                <li class="breadcrumb-item"><a href="{{ route('paket.index') }}" style="color:#173F35;">Paket Sewa</a></li>
                <li class="breadcrumb-item active">{{ $paket->nama_paket }}</li>
            </ol>
        </nav>
    </div>
</div>

<section class="py-5 bg-white">
    <div class="container">
        <div class="row g-5">
            {{-- Image & quick info --}}
            <div class="col-lg-5">
                @php
                    $paketImagePath = 'uploads/paket/' . basename($paket->gambar ?? '');
                    $paketImageUrl = $paket->gambar && is_file(public_path($paketImagePath))
                        ? asset($paketImagePath)
                        : ($paket->gambar ? Storage::disk('public')->url($paket->gambar) : null);
                @endphp
                @if($paketImageUrl)
                    <img src="{{ $paketImageUrl }}" alt="{{ $paket->nama_paket }}"
                        class="w-100 rounded-4 shadow-sm" style="height:380px;object-fit:cover;">
                @else
                    <div class="rounded-4 d-flex align-items-center justify-content-center shadow-sm"
                        style="height:380px;background:linear-gradient(135deg,#173F35,#2d6a4f);">
                        <i class="bi bi-boxes text-white" style="font-size:7rem;opacity:0.4;"></i>
                    </div>
                @endif

                {{-- Stats --}}
                <div class="row g-3 mt-3">
                    <div class="col-6">
                        <div class="p-3 rounded-3 text-center border">
                            <i class="bi bi-people-fill fs-4 mb-1 d-block" style="color:#173F35;"></i>
                            <div class="fw-bold" style="color:#173F35;">{{ $paket->kapasitas_orang }}</div>
                            <div class="text-muted small">Orang</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 rounded-3 text-center border">
                            <i class="bi bi-box-seam fs-4 mb-1 d-block" style="color:#173F35;"></i>
                            <div class="fw-bold" style="color:#173F35;">{{ $paket->detailPaket->count() }}</div>
                            <div class="text-muted small">Item Alat</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Details --}}
            <div class="col-lg-7">
                <span class="badge mb-3 px-3 py-2 rounded-pill" style="background:#e8f5e9;color:#173F35;">
                    <i class="bi bi-boxes me-1"></i> Paket Sewa
                </span>
                <h1 class="fw-bold mb-2" style="color:#173F35;font-size:2rem;">{{ $paket->nama_paket }}</h1>
                <div class="mb-4">
                    <div class="text-muted small mb-1">Harga per hari</div>
                    @if($hematPaket > 0)
                        <div class="text-muted text-decoration-line-through small">
                            {{ formatRupiah($hargaNormalPaket) }}/hari harga satuan
                        </div>
                    @endif
                    <div class="fw-bold" style="color:#173F35;font-size:2.2rem;">
                        Rp {{ number_format($paket->harga_paket_per_hari, 0, ',', '.') }}
                        <span class="text-muted fw-normal fs-6">/hari</span>
                    </div>
                    @if($hematPaket > 0)
                        <div class="text-success small fw-semibold mt-1">
                            Hemat {{ formatRupiah($hematPaket) }}/hari ({{ $persenHematPaket }}%)
                        </div>
                    @endif
                </div>

                @if($paket->deskripsi)
                <p class="text-muted lh-lg mb-4">{{ $paket->deskripsi }}</p>
                @endif

                {{-- Items List --}}
                <div class="mb-4">
                    <h5 class="fw-bold mb-3" style="color:#173F35;">
                        <i class="bi bi-list-check me-2"></i>Isi Paket
                    </h5>
                    @if($paket->detailPaket->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover rounded-3 overflow-hidden">
                            <thead style="background:#173F35;color:white;">
                                <tr>
                                    <th>Nama Alat</th>
                                    <th class="text-center">Jumlah</th>
                                    <th>Kategori</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($paket->detailPaket as $dp)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            @if($dp->alatHiking->gambar ?? false)
                                                <img src="{{ Storage::url($dp->alatHiking->gambar) }}"
                                                    class="rounded" style="width:35px;height:35px;object-fit:cover;">
                                            @else
                                                <div class="rounded bg-light d-flex align-items-center justify-content-center"
                                                    style="width:35px;height:35px;">
                                                    <i class="bi bi-box text-muted small"></i>
                                                </div>
                                            @endif
                                            <div class="fw-semibold small">{{ $dp->alatHiking->nama_alat ?? '-' }}</div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge rounded-pill" style="background:#e8f5e9;color:#173F35;">
                                            {{ $dp->jumlah ?? 1 }} unit
                                        </span>
                                    </td>
                                    <td class="small text-muted">
                                        {{ $dp->alatHiking->kategori->nama_kategori ?? '-' }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <p class="text-muted small">Belum ada detail item dalam paket ini.</p>
                    @endif
                </div>

                {{-- CTA --}}
                @auth
                    <a href="{{ route('booking.create', ['paket_id' => $paket->id_paket]) }}" class="btn btn-brand btn-lg w-100 py-3 fw-bold">
                        <i class="bi bi-bag-plus me-2"></i> Sewa Paket Ini
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-brand btn-lg w-100 py-3 fw-bold">
                        <i class="bi bi-box-arrow-in-right me-2"></i> Login untuk Menyewa
                    </a>
                @endauth
                <div class="text-center mt-2">
                    <a href="{{ route('paket.index') }}" class="text-muted small text-decoration-none">
                        <i class="bi bi-arrow-left me-1"></i> Kembali ke daftar paket
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

