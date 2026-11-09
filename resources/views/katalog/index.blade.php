@extends('layouts.app')

@section('content')
<!-- Header Banner -->
<div class="py-4" style="background-color: var(--forest-green); color: #ffffff;">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-1">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white-50 text-decoration-none">Beranda</a></li>
                <li class="breadcrumb-item active text-white" aria-current="page">Katalog Alat</li>
            </ol>
        </nav>
        <h2 class="fw-bold mb-0">Katalog Perlengkapan Outdoor</h2>
        <p class="text-white-50 small mb-0">Peralatan pendakian berkualitas tinggi, bersih, dan siap menemani petualangan Anda.</p>
    </div>
</div>

<div class="py-5">
    <div class="container">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row g-4">
            <!-- Sidebar Filter -->
            <div class="col-lg-3">
                <div class="card border-0 shadow-sm rounded-4 p-4 sticky-top" style="top: 90px; z-index: 5;">
                    <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-2">
                        <h6 class="fw-bold mb-0"><i class="bi bi-funnel-fill text-warning me-2"></i>Filter Pencarian</h6>
                        <a href="{{ route('katalog.index') }}" class="small text-muted text-decoration-none">Reset</a>
                    </div>

                    <form action="{{ route('katalog.index') }}" method="GET">
                        <!-- Search Keyword -->
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Kata Kunci</label>
                            <input type="text" name="q" class="form-control form-control-sm" placeholder="Nama alat / merk..." value="{{ request('q') }}">
                        </div>

                        <!-- Kategori Filter -->
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Kategori</label>
                            <select name="kategori" class="form-select form-select-sm">
                                <option value="">Semua Kategori</option>
                                @foreach($allKategori as $kat)
                                    <option value="{{ $kat->slug }}" {{ request('kategori') == $kat->slug ? 'selected' : '' }}>
                                        {{ $kat->nama_kategori }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Urutan -->
                        <div class="mb-4">
                            <label class="form-label small fw-semibold">Urutkan</label>
                            <select name="sort" class="form-select form-select-sm">
                                <option value="terbaru" {{ request('sort') == 'terbaru' ? 'selected' : '' }}>Terbaru</option>
                                <option value="harga_asc" {{ request('sort') == 'harga_asc' ? 'selected' : '' }}>Harga: Rendah ke Tinggi</option>
                                <option value="harga_desc" {{ request('sort') == 'harga_desc' ? 'selected' : '' }}>Harga: Tinggi ke Rendah</option>
                                <option value="nama_asc" {{ request('sort') == 'nama_asc' ? 'selected' : '' }}>Nama (A - Z)</option>
                            </select>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-brand btn-sm py-2">
                                <i class="bi bi-search me-1"></i> Terapkan Filter
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- List Produk -->
            <div class="col-lg-9">
                <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                    <div class="small text-muted">
                        Menampilkan <strong>{{ $items->count() }}</strong> dari <strong>{{ $items->total() }}</strong> perlengkapan
                        @if(request('q'))
                            untuk pencarian "<strong>{{ request('q') }}</strong>"
                        @endif
                    </div>
                </div>

                @if($items->isEmpty())
                    <div class="card border-0 shadow-sm rounded-4 p-5 text-center">
                        <i class="bi bi-search fs-1 text-muted mb-3"></i>
                        <h5 class="fw-bold">Tidak ada perlengkapan yang cocok</h5>
                        <p class="text-muted small">Coba ubah kata kunci atau reset filter kategori Anda.</p>
                        <div class="mt-2">
                            <a href="{{ route('katalog.index') }}" class="btn btn-outline-forest btn-sm px-4">Lihat Semua Alat</a>
                        </div>
                    </div>
                @else
                    <div class="row g-4">
                        @foreach($items as $item)
                            @php
                                $avail = getItemAvailability($item->id_alat);
                                $imgSrc = getProductImageUrl($item);
                            @endphp
                            <div class="col-md-6 col-lg-4">
                                <div class="gear-card">
                                    <div class="gear-img-wrapper">
                                        <img src="{{ $imgSrc }}" alt="{{ $item->nama_alat }}" loading="lazy">
                                        <span class="gear-badge-category">{{ $item->kategori->nama_kategori }}</span>
                                        @if($avail['sisa_stok'] > 0)
                                            <span class="gear-badge-stock bg-success text-white">
                                                <i class="bi bi-check2"></i> Sisa {{ $avail['sisa_stok'] }} Unit
                                            </span>
                                        @else
                                            <span class="gear-badge-stock bg-danger text-white">
                                                <i class="bi bi-x"></i> Habis
                                            </span>
                                        @endif
                                    </div>
                                    <div class="p-3 d-flex flex-column flex-grow-1">
                                        <div class="text-muted small fw-semibold text-uppercase mb-1">{{ $item->merk }}</div>
                                        <h5 class="fw-bold mb-2">
                                            <a href="{{ route('katalog.show', $item->slug) }}" class="text-dark text-decoration-none">
                                                {{ $item->nama_alat }}
                                            </a>
                                        </h5>
                                        <p class="text-muted small mb-3 flex-grow-1">
                                            {{ Str::limit($item->deskripsi, 85) }}
                                        </p>
                                        <div class="d-flex align-items-center justify-content-between pt-2 border-top">
                                            <div>
                                                <span class="text-muted small d-block">Harga Sewa:</span>
                                                <div class="gear-price-tag">{{ formatRupiah($item->harga_per_hari) }}<small class="text-muted fs-6 fw-normal">/hari</small></div>
                                            </div>
                                            <div class="d-flex gap-1">
                                                <a href="{{ route('katalog.show', $item->slug) }}" class="btn btn-outline-secondary btn-sm" title="Lihat Spesifikasi">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                @if($avail['sisa_stok'] > 0)
                                                    <a href="{{ route('booking.create', ['alat_id' => $item->id_alat]) }}" class="btn btn-brand btn-sm px-3">
                                                        Sewa <i class="bi bi-cart-plus ms-1"></i>
                                                    </a>
                                                @else
                                                    <button class="btn btn-secondary btn-sm px-3" disabled title="Stok saat ini kosong">Habis</button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <nav class="mt-5" aria-label="Navigasi Halaman">
                        {{ $items->appends(request()->query())->links('pagination::bootstrap-5') }}
                    </nav>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
