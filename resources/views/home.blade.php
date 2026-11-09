@extends('layouts.app')

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="container position-relative z-1">
        <div class="row align-items-center">
            <div class="col-lg-8 mx-auto text-center">
                <span class="hero-badge">
                    <i class="bi bi-fire"></i> Rental Outdoor Terlengkap & Terpercaya
                </span>
                <h1 class="display-4 fw-bold text-white mb-3">
                    Petualangan Besar Dimulai dari Perlengkapan yang Tepat.
                </h1>
                <p class="lead text-white-50 mb-4 px-md-4">
                    Sewa tenda waterproof, carrier ergonomis, sleeping bag hangat, kompor portable, dan peralatan pendakian gunung berkualitas standar ekspedisi dengan harga hemat dan proses reservasi mudah.
                </p>
                <div class="d-flex justify-content-center gap-3 flex-wrap">
                    <a href="{{ route('katalog.index') }}" class="btn btn-brand px-4 py-3 fs-6">
                        <i class="bi bi-grid-3x3-gap-fill me-2"></i> Jelajahi Peralatan
                    </a>
                    <a href="{{ route('paket.index') }}" class="btn btn-outline-light px-4 py-3 fs-6">
                        <i class="bi bi-box-seam me-2"></i> Lihat Paket Sewa Hemat
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Floating Search Bar -->
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="search-floating-card">
                <form action="{{ route('katalog.index') }}" method="GET" class="row g-2 align-items-center">
                    <div class="col-md-5">
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-search"></i></span>
                            <input type="text" name="q" class="form-control bg-light border-start-0 py-2" placeholder="Cari tenda, carrier, merk Eiger, dll..." value="{{ request('q') }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-tag"></i></span>
                            <select name="kategori" class="form-select bg-light border-start-0 py-2">
                                <option value="">Semua Kategori</option>
                                @foreach($kategoriList as $kat)
                                    <option value="{{ $kat->slug }}" {{ request('kategori') == $kat->slug ? 'selected' : '' }}>
                                        {{ $kat->nama_kategori }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-brand w-100 py-2 fw-semibold">
                            <i class="bi bi-funnel me-1"></i> Temukan Gear
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<main class="py-5">
    <div class="container">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show d-flex align-items-center mb-4" role="alert">
                <i class="bi bi-check-circle-fill fs-5 me-2"></i>
                <div>{{ session('success') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center mb-4" role="alert">
                <i class="bi bi-exclamation-triangle-fill fs-5 me-2"></i>
                <div>{{ session('error') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Kategori Section -->
        <div class="mb-5 mt-4">
            <div class="text-center mb-4">
                <span class="text-uppercase fw-bold text-success small tracking-wider">Kategori Peralatan</span>
                <h2 class="fw-bold text-dark mt-1">Peralatan Berdasarkan Kebutuhan Ekspedisi</h2>
                <p class="text-muted">Pilih perlengkapan sesuai spesifikasi rute pendakian dan durasi perjalanan Anda</p>
            </div>

            <div class="row g-3">
                @foreach($kategoriList as $kat)
                    <div class="col-6 col-md-4 col-lg-2">
                        <a href="{{ route('katalog.index', ['kategori' => $kat->slug]) }}" class="category-card h-100">
                            <div class="category-icon">
                                <i class="bi {{ $kat->icon ?? 'bi-compass' }}"></i>
                            </div>
                            <h6 class="fw-bold mb-1">{{ $kat->nama_kategori }}</h6>
                            <small class="text-muted">{{ $kat->alat_hiking_count ?? 0 }} Item</small>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Produk Populer Section -->
        <div class="mb-5 pt-3">
            <div class="d-flex justify-content-between align-items-end mb-4 flex-wrap gap-2">
                <div>
                    <span class="text-uppercase fw-bold text-success small tracking-wider">Koleksi Terpopuler</span>
                    <h2 class="fw-bold text-dark mt-1 mb-0">Peralatan Paling Banyak Disewa</h2>
                </div>
                <a href="{{ route('katalog.index') }}" class="btn btn-outline-forest btn-sm px-3">
                    Lihat Semua Katalog <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>

            <div class="row g-4">
                @foreach($produkPopuler as $item)
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
                                    {{ Str::limit($item->deskripsi, 95) }}
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
        </div>

        <!-- Banner Paket Sewa Hemat -->
        <div class="mb-5 pt-3">
            <div class="card border-0 text-white rounded-4 overflow-hidden" style="background: linear-gradient(135deg, #102D27, #173F35);">
                <div class="card-body p-4 p-md-5">
                    <div class="row align-items-center">
                        <div class="col-lg-8">
                            <span class="badge bg-warning text-dark px-3 py-2 mb-3 fw-bold">PROMO BUNDLE LENGKAP</span>
                            <h2 class="display-6 fw-bold mb-2 text-white">Hemat Lebih Banyak dengan Paket Pendakian</h2>
                            <p class="text-white-50 mb-4">
                                Tidak perlu repot memilih satu per satu. Paket sewa kami telah dikurasi oleh pemandu gunung berpengalaman untuk kebutuhan solo hiker, pasangan, rombongan 4 orang, hingga keluarga.
                            </p>
                            <a href="{{ route('paket.index') }}" class="btn btn-brand px-4 py-2">
                                <i class="bi bi-box-seam me-2"></i> Lihat Semua Pilihan Paket Sewa
                            </a>
                        </div>
                        <div class="col-lg-4 text-center mt-4 mt-lg-0">
                            <div class="p-3 bg-white bg-opacity-10 rounded-4 border border-white border-opacity-25">
                                @if($paketDiskonTerbesar > 0)
                                    <div class="display-5 fw-bold text-warning mb-1">Hemat {{ $paketDiskonTerbesar }}%</div>
                                    <div class="small text-white-50">Dibandingkan harga sewa satuan per hari</div>
                                @else
                                    <div class="fs-4 fw-bold text-warning mb-1">Paket Aktif</div>
                                    <div class="small text-white-50">Lihat pilihan perlengkapan dalam satu paket</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($paketList->isNotEmpty())
            <div class="mb-5">
                <div class="d-flex justify-content-between align-items-end gap-3 mb-3 flex-wrap">
                    <div>
                        <span class="text-uppercase fw-bold text-success small">Paket Hemat</span>
                        <h3 class="fw-bold text-dark mb-0">Pilih paket pendakian</h3>
                    </div>
                    <a href="{{ route('paket.index') }}" class="btn btn-outline-forest btn-sm">
                        Semua paket <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
                <div class="row g-3">
                    @foreach($paketList as $paket)
                        @php
                            $hargaNormal = $paket->detailPaket->sum(
                                fn ($detail) => ($detail->alatHiking?->harga_per_hari ?? 0) * $detail->jumlah_unit
                            );
                            $hemat = max(0, $hargaNormal - $paket->harga_paket_per_hari);
                            $gambarPaketPath = 'uploads/paket/' . basename($paket->gambar ?? '');
                            $gambarPaketUrl = $paket->gambar && is_file(public_path($gambarPaketPath))
                                ? asset($gambarPaketPath)
                                : ($paket->gambar ? asset('storage/' . $paket->gambar) : asset('assets/images/logo.svg'));
                        @endphp
                        <div class="col-md-6 col-xl-3">
                            <article class="card h-100 border-0 shadow-sm rounded-3 overflow-hidden">
                                <img src="{{ $gambarPaketUrl }}" alt="{{ $paket->nama_paket }}" class="w-100" style="height: 170px; object-fit: cover;">
                                <div class="card-body d-flex flex-column">
                                    <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
                                        <h5 class="fw-bold mb-0">{{ $paket->nama_paket }}</h5>
                                        <span class="badge bg-light text-dark flex-shrink-0">
                                            {{ $paket->kapasitas_orang }} org
                                        </span>
                                    </div>
                                    <p class="small text-muted flex-grow-1">{{ \Illuminate\Support\Str::limit($paket->deskripsi, 90) }}</p>
                                    @if($hemat > 0)
                                        <div class="small text-success fw-semibold mb-2">
                                            Hemat {{ formatRupiah($hemat) }}/hari
                                        </div>
                                    @endif
                                    <div class="d-flex justify-content-between align-items-end border-top pt-3 mt-auto">
                                        <div>
                                            <small class="text-muted d-block">Harga per hari</small>
                                            <strong class="text-success">{{ formatRupiah($paket->harga_paket_per_hari) }}</strong>
                                        </div>
                                        <a href="{{ route('booking.create', ['paket_id' => $paket->id_paket]) }}" class="btn btn-brand btn-sm">
                                            Pilih paket
                                        </a>
                                    </div>
                                </div>
                            </article>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Cara Menyewa dalam 4 Langkah -->
        <div class="mb-5 pt-3">
            <div class="text-center mb-5">
                <span class="text-uppercase fw-bold text-success small tracking-wider">Alur Booking Sederhana</span>
                <h2 class="fw-bold text-dark mt-1">Cara Menyewa di HIKETRIP RENTAL</h2>
                <p class="text-muted">Proses cepat, transparan, dan tidak merepotkan persiapan pendakian Anda</p>
            </div>

            <div class="row g-4 text-center">
                <div class="col-md-3">
                    <div class="p-3">
                        <div class="category-icon mx-auto mb-3" style="width: 64px; height: 64px; font-size: 1.8rem;">
                            <i class="bi bi-search text-warning"></i>
                        </div>
                        <h5 class="fw-bold">1. Pilih Perlengkapan</h5>
                        <p class="text-muted small">Cari peralatan atau paket yang Anda butuhkan melalui katalog online kami.</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="p-3">
                        <div class="category-icon mx-auto mb-3" style="width: 64px; height: 64px; font-size: 1.8rem;">
                            <i class="bi bi-calendar2-range text-warning"></i>
                        </div>
                        <h5 class="fw-bold">2. Tentukan Tanggal</h5>
                        <p class="text-muted small">Tentukan tanggal mulai dan kembali. Sistem menghitung durasi dan biaya real-time.</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="p-3">
                        <div class="category-icon mx-auto mb-3" style="width: 64px; height: 64px; font-size: 1.8rem;">
                            <i class="bi bi-shield-lock text-warning"></i>
                        </div>
                        <h5 class="fw-bold">3. Unggah Jaminan</h5>
                        <p class="text-muted small">Upload foto dokumen KTP/SIM sebagai jaminan sewa yang aman dan terenkripsi.</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="p-3">
                        <div class="category-icon mx-auto mb-3" style="width: 64px; height: 64px; font-size: 1.8rem;">
                            <i class="bi bi-geo-alt text-warning"></i>
                        </div>
                        <h5 class="fw-bold">4. Ambil & Jelajahi</h5>
                        <p class="text-muted small">Ambil di basecamp kami atau pilih kirim kurir, lalu nikmati petualangan Anda!</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Keunggulan Layanan -->
        <div class="row g-4 mb-5 pt-3">
            <div class="col-md-4">
                <div class="card h-100 border-0 bg-white p-4 rounded-4 shadow-sm">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="bg-success bg-opacity-10 text-success p-3 rounded-3 fs-3">
                            <i class="bi bi-patch-check-fill"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-0">Perlengkapan Terawat</h5>
                            <small class="text-muted">Laundry & QC Ketat</small>
                        </div>
                    </div>
                    <p class="text-muted small mb-0">
                        Setiap tenda dibersihkan dan dicek frame serta flysheet-nya. Sleeping bag selalu dicuci higienis sehingga siap pakai tanpa bau apek.
                    </p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border-0 bg-white p-4 rounded-4 shadow-sm">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="bg-warning bg-opacity-10 text-warning p-3 rounded-3 fs-3">
                            <i class="bi bi-tags-fill"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-0">Harga Transparan</h5>
                            <small class="text-muted">Tanpa Biaya Tersembunyi</small>
                        </div>
                    </div>
                    <p class="text-muted small mb-0">
                        Perhitungan hari sewa jelas dan transparan. Tidak ada biaya tambahan yang tiba-tiba muncul saat pengembalian barang tepat waktu.
                    </p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border-0 bg-white p-4 rounded-4 shadow-sm">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="bg-primary bg-opacity-10 text-primary p-3 rounded-3 fs-3">
                            <i class="bi bi-truck"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-0">Fleksibilitas Pengambilan</h5>
                            <small class="text-muted">Ambil di Toko / Kurir</small>
                        </div>
                    </div>
                    <p class="text-muted small mb-0">
                        Bebas pilih ambil langsung di outlet basecamp strategis kami atau kirim cepat langsung ke alamat rumah atau titik kumpul Anda.
                    </p>
                </div>
            </div>
        </div>

        <!-- FAQ Accordion -->
        <div class="mb-5 pt-3">
            <div class="text-center mb-4">
                <span class="text-uppercase fw-bold text-success small tracking-wider">Tanya Jawab</span>
                <h2 class="fw-bold text-dark mt-1">Pertanyaan yang Sering Diajukan (FAQ)</h2>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-9">
                    <div class="accordion" id="accordionFaq">
                        <div class="accordion-item border-0 mb-3 shadow-sm rounded-3 overflow-hidden">
                            <h2 class="accordion-header">
                                <button class="accordion-button fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                    Dokumen apa saja yang dapat dijadikan sebagai jaminan sewa?
                                </button>
                            </h2>
                            <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#accordionFaq">
                                <div class="accordion-body text-muted small">
                                    Penyewa wajib mengunggah foto kartu identitas asli yang masih berlaku (KTP Elektronik, SIM, atau Paspor) saat reservasi, dan menunjukkan kartu fisik asli saat serah terima barang di basecamp.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item border-0 mb-3 shadow-sm rounded-3 overflow-hidden">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                    Bagaimana jika saya terlambat mengembalikan perlengkapan?
                                </button>
                            </h2>
                            <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#accordionFaq">
                                <div class="accordion-body text-muted small">
                                    Batas pengembalian adalah pukul 21:00 WIB pada tanggal kembali. Keterlambatan pengembalian tanpa konfirmasi sebelumnya akan dikenakan denda keterlambatan flat sebesar Rp 50.000 per hari sesuai kebijakan toko.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item border-0 mb-3 shadow-sm rounded-3 overflow-hidden">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                    Apakah saya boleh membatalkan pesanan yang sudah diajukan?
                                </button>
                            </h2>
                            <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#accordionFaq">
                                <div class="accordion-body text-muted small">
                                    Ya. Selama status pengajuan Anda masih <strong>Pending (Menunggu Persetujuan)</strong>, Anda dapat membatalkan pesanan secara mandiri dengan satu klik melalui Dashboard Customer Anda.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item border-0 mb-3 shadow-sm rounded-3 overflow-hidden">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">
                                    Bagaimana jika ada alat yang sobek, patah, atau hilang di gunung?
                                </button>
                            </h2>
                            <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#accordionFaq">
                                <div class="accordion-body text-muted small">
                                    Saat pengembalian, staf kami akan melakukan pengecekan bersama Anda. Kerusakan kecil yang wajar tidak dikenakan biaya. Namun untuk frame patah, kain robek, atau unit hilang, dikenakan biaya perbaikan atau ganti rugi sesuai taksiran nilai unit yang disepakati.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</main>
@endsection
