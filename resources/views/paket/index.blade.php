@extends('layouts.app')

@section('content')
<!-- Header Banner -->
<div class="py-4" style="background-color: var(--forest-green); color: #ffffff;">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-1">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white-50 text-decoration-none">Beranda</a></li>
                <li class="breadcrumb-item active text-white" aria-current="page">Paket Sewa</li>
            </ol>
        </nav>
        <h2 class="fw-bold mb-0">Paket Hemat Pendakian</h2>
        <p class="text-white-50 small mb-0">Solusi praktis dan hemat biaya tanpa repot memilih peralatan satu per satu.</p>
    </div>
</div>

<main class="py-5">
    <div class="container">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row g-4">
            @foreach($packages as $pkg)
                @php
                    // Ambil items dalam paket
                    $itemsInPackage = \App\Models\DetailPaket::where('id_paket', $pkg->id_paket)
                        ->with('alatHiking')
                        ->get();
                    
                    // Hitung total normal vs harga paket
                    $normalTotal = 0;
                    $isPackageAvailable = true;
                    $stockWarning = '';
                    
                    foreach ($itemsInPackage as $item) {
                        $normalTotal += ($item->alatHiking->harga_per_hari * $item->jumlah_unit);
                        $avail = getItemAvailability($item->id_alat);
                        if ($avail['sisa_stok'] < $item->jumlah_unit) {
                            $isPackageAvailable = false;
                            $stockWarning = "Komponen '{$item->alatHiking->nama_alat}' stok terbatas.";
                        }
                    }
                    
                    $hemat = max(0, $normalTotal - $pkg->harga_paket_per_hari);
                    $imgSrc = file_exists(public_path('uploads/paket/' . $pkg->gambar)) 
                        ? asset('uploads/paket/' . $pkg->gambar) 
                        : asset('assets/images/logo.svg');
                @endphp
                
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100 bg-white d-flex flex-column">
                        <div class="row g-0 flex-grow-1">
                            <div class="col-md-5 position-relative">
                                <img src="{{ $imgSrc }}" alt="{{ $pkg->nama_paket }}" class="w-100 h-100" style="object-fit: cover; min-height: 250px;">
                                <span class="badge bg-warning text-dark position-absolute top-0 start-0 m-3 px-3 py-2 fw-bold">
                                    <i class="bi bi-people-fill me-1"></i> Kapasitas {{ $pkg->kapasitas_orang }} Orang
                                </span>
                            </div>
                            <div class="col-md-7 p-4 d-flex flex-column justify-content-between">
                                <div>
                                    <h4 class="fw-bold mb-2">{{ $pkg->nama_paket }}</h4>
                                    <p class="text-muted small mb-3">{{ $pkg->deskripsi }}</p>

                                    <h6 class="fw-bold small text-uppercase text-secondary mb-2">Item Termasuk Dalam Paket:</h6>
                                    <ul class="list-group list-group-flush small mb-3">
                                        @foreach($itemsInPackage as $item)
                                            @php
                                                $itemAvail = getItemAvailability($item->id_alat);
                                            @endphp
                                            <li class="list-group-item px-0 py-1 bg-transparent d-flex justify-content-between align-items-center">
                                                <span>
                                                    <i class="bi bi-check-circle-fill text-success me-1"></i>
                                                    <strong>{{ $item->jumlah_unit }}x</strong> {{ $item->alatHiking->nama_alat }}
                                                </span>
                                                @if($itemAvail['sisa_stok'] < $item->jumlah_unit)
                                                    <span class="badge bg-danger-subtle text-danger small">Stok tipis</span>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>

                                <div class="pt-3 border-top">
                                    <div class="d-flex justify-content-between align-items-end mb-3">
                                        <div>
                                            @if($hemat > 0)
                                                <small class="text-decoration-line-through text-muted d-block">{{ formatRupiah($normalTotal) }}/hari</small>
                                            @endif
                                            <div class="fs-4 fw-bold text-success font-monospace">{{ formatRupiah($pkg->harga_paket_per_hari) }}<small class="text-muted fs-6 fw-normal">/hari</small></div>
                                        </div>
                                        @if($hemat > 0)
                                            <span class="badge bg-success-subtle text-success border border-success px-2 py-1 small">
                                                Hemat {{ formatRupiah($hemat) }}/hari
                                            </span>
                                        @endif
                                    </div>

                                    @if($isPackageAvailable)
                                        <a href="{{ route('booking.create', ['paket_id' => $pkg->id_paket]) }}" class="btn btn-brand w-100 py-2">
                                            <i class="bi bi-cart-plus me-1"></i> Sewa Paket Ini Sekarang
                                        </a>
                                    @else
                                        <button class="btn btn-secondary w-100 py-2" disabled title="{{ $stockWarning }}">
                                            <i class="bi bi-exclamation-triangle me-1"></i> Komponen Paket Kurang
                                        </button>
                                        <div class="text-danger small mt-1 text-center">{{ $stockWarning }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</main>
@endsection
