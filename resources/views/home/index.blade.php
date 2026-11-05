@extends('layouts.app')

@section('title', 'Beranda')

@push('styles')
<style>
/* Hero Section */
.hero-section {
    background: linear-gradient(160deg, #0d2b22 0%, #173F35 50%, #1a4d3d 100%);
    min-height: 90vh;
    display: flex;
    align-items: center;
    position: relative;
    overflow: hidden;
}
.hero-section::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0; bottom: 0;
    background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1440 600'%3E%3Cpath fill='%23ffffff08' d='M0,400L60,380C120,360,240,320,360,290C480,260,600,240,720,250C840,260,960,300,1080,310C1200,320,1320,300,1380,290L1440,280L1440,600L0,600Z'/%3E%3C/svg%3E") no-repeat bottom;
    background-size: cover;
}
.hero-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: rgba(255,193,7,0.15);
    border: 1px solid rgba(255,193,7,0.3);
    color: #ffc107;
    padding: 0.4rem 1rem;
    border-radius: 50px;
    font-size: 0.85rem;
    font-weight: 600;
    margin-bottom: 1.5rem;
}
.hero-title {
    font-size: clamp(2.5rem, 5vw, 4rem);
    font-weight: 800;
    line-height: 1.1;
    color: white;
    margin-bottom: 1.5rem;
}
.hero-title span {
    color: #ffc107;
    display: block;
}
.hero-search-box {
    background: white;
    border-radius: 16px;
    padding: 0.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    box-shadow: 0 20px 60px rgba(0,0,0,0.3);
    max-width: 600px;
}
.hero-search-box input {
    border: none;
    outline: none;
    flex: 1;
    padding: 0.75rem 1rem;
    font-size: 1rem;
    background: transparent;
}
.hero-stats {
    display: flex;
    gap: 2rem;
    margin-top: 2rem;
    flex-wrap: wrap;
}
.hero-stat {
    text-align: center;
}
.hero-stat .num {
    font-size: 1.75rem;
    font-weight: 800;
    color: #ffc107;
    display: block;
}
.hero-stat .label {
    font-size: 0.8rem;
    color: rgba(255,255,255,0.65);
}
/* Kategori */
.kategori-card {
    border: 2px solid transparent;
    border-radius: 16px;
    padding: 2rem 1rem;
    text-align: center;
    transition: all 0.3s;
    cursor: pointer;
    background: white;
    text-decoration: none;
    display: block;
    color: #333;
}
.kategori-card:hover {
    border-color: #173F35;
    transform: translateY(-5px);
    box-shadow: 0 15px 40px rgba(23,63,53,0.15);
    color: #173F35;
}
.kategori-icon {
    width: 70px;
    height: 70px;
    border-radius: 50%;
    background: linear-gradient(135deg, #e8f5e9, #c8e6c9);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.75rem;
    margin: 0 auto 1rem;
    transition: all 0.3s;
    color: #173F35;
}
.kategori-card:hover .kategori-icon {
    background: linear-gradient(135deg, #173F35, #2d6a4f);
    color: white;
}
/* Product card */
.gear-card {
    border: none;
    border-radius: 16px;
    overflow: hidden;
    transition: all 0.3s;
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
}
.gear-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 50px rgba(23,63,53,0.15);
}
.gear-img-wrapper {
    height: 200px;
    overflow: hidden;
    background: #f8f9fa;
    position: relative;
}
.gear-img-wrapper img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s;
}
.gear-card:hover .gear-img-wrapper img {
    transform: scale(1.07);
}
.gear-badge {
    position: absolute;
    top: 10px;
    left: 10px;
    font-size: 0.7rem;
    padding: 0.3rem 0.6rem;
    border-radius: 50px;
}
.kondisi-baik { background: #d4edda; color: #155724; }
.kondisi-baik-baik { background: #cce5ff; color: #004085; }
.kondisi-rusak { background: #f8d7da; color: #721c24; }
.price-tag {
    color: #173F35;
    font-weight: 700;
    font-size: 1.1rem;
}
/* Paket sewa */
.paket-card {
    border: none;
    border-radius: 20px;
    overflow: hidden;
    transition: all 0.3s;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
}
.paket-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 50px rgba(23,63,53,0.2);
}
.paket-img {
    height: 200px;
    object-fit: cover;
    width: 100%;
}
/* Cara Sewa */
.step-card {
    text-align: center;
    padding: 2rem 1.5rem;
    position: relative;
}
.step-num {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: linear-gradient(135deg, #173F35, #2d6a4f);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    font-weight: 800;
    margin: 0 auto 1.25rem;
    box-shadow: 0 8px 20px rgba(23,63,53,0.3);
}
/* Section titles */
.section-title {
    font-size: 2rem;
    font-weight: 800;
    color: #173F35;
    margin-bottom: 0.5rem;
}
.section-subtitle {
    color: #6c757d;
    margin-bottom: 3rem;
}
</style>
@endpush

@section('content')

{{-- HERO SECTION --}}
<section class="hero-section py-5">
    <div class="container position-relative">
        <div class="row align-items-center g-5">
            <div class="col-lg-7" data-aos="fade-right">
                <div class="hero-badge">
                    <i class="bi bi-stars"></i>
                    Platform Rental Hiking #1 Terpercaya
                </div>
                <h1 class="hero-title">
                    Sewa Gear Hiking
                    <span>Berkualitas Tinggi</span>
                </h1>
                <p class="text-white-75 fs-5 mb-4" style="max-width:520px;">
                    Lengkapi petualangan Anda dengan peralatan hiking premium. Tenda, carrier, sleeping bag, dan lebih dari 50+ alat siap disewa!
                </p>
                <!-- Search Box -->
                <form action="{{ route('katalog.index') }}" method="GET">
                    <div class="hero-search-box">
                        <i class="bi bi-search text-muted ms-2 fs-5"></i>
                        <input type="text" name="q" placeholder="Cari alat hiking (tenda, carrier, sleeping bag...)">
                        <button type="submit" class="btn btn-brand px-4 py-2 rounded-3">
                            Cari
                        </button>
                    </div>
                </form>
                <!-- Stats -->
                <div class="hero-stats">
                    <div class="hero-stat">
                        <span class="num">50+</span>
                        <span class="label">Alat Tersedia</span>
                    </div>
                    <div class="hero-stat">
                        <span class="num">100+</span>
                        <span class="label">Pelanggan Puas</span>
                    </div>
                    <div class="hero-stat">
                        <span class="num">5★</span>
                        <span class="label">Rating Layanan</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-5 d-none d-lg-flex justify-content-center">
                <div class="position-relative">
                    <div class="rounded-4 overflow-hidden" style="width:400px;height:450px;background:rgba(255,255,255,0.08);display:flex;align-items:center;justify-content:center;">
                        <i class="bi bi-mountains" style="font-size:12rem;color:rgba(255,255,255,0.1);"></i>
                        <div class="position-absolute text-center text-white">
                            <i class="bi bi-compass text-warning" style="font-size:5rem;"></i>
                            <div class="fw-bold fs-5 mt-2">Siap Untuk Mendaki?</div>
                        </div>
                    </div>
                    <!-- Floating cards -->
                    <div class="position-absolute top-0 start-0 bg-white rounded-3 p-3 shadow-lg" style="transform:translate(-40px,-10px);">
                        <div class="d-flex align-items-center gap-2 small">
                            <i class="bi bi-check-circle-fill text-success fs-5"></i>
                            <div>
                                <div class="fw-bold" style="font-size:0.75rem;">Stok Tersedia</div>
                                <div class="text-muted" style="font-size:0.65rem;">Tenda Dome 2P</div>
                            </div>
                        </div>
                    </div>
                    <div class="position-absolute bottom-0 end-0 bg-warning rounded-3 p-3 shadow-lg" style="transform:translate(20px,20px);">
                        <div class="text-dark small fw-bold">Mulai dari</div>
                        <div class="text-dark fw-bold fs-5">Rp 15.000<span class="fs-6">/hari</span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- KATEGORI SECTION --}}
<section class="py-5 bg-white">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">Kategori Alat</h2>
            <p class="section-subtitle">Temukan alat hiking berdasarkan kategori kebutuhan Anda</p>
        </div>
        <div class="row g-3 justify-content-center">
            <div class="col-lg-2 col-md-3 col-4">
                <a href="{{ route('katalog.index') }}" class="kategori-card shadow-sm">
                    <div class="kategori-icon">
                        <i class="bi bi-grid-fill"></i>
                    </div>
                    <div class="fw-semibold small">Semua Alat</div>
                </a>
            </div>
            @foreach($kategoris as $kat)
            <div class="col-lg-2 col-md-3 col-4">
                <a href="{{ route('katalog.index', ['kategori' => $kat->id_kategori]) }}" class="kategori-card shadow-sm">
                    <div class="kategori-icon">
                        <i class="bi bi-{{ $kat->icon ?? 'box' }}"></i>
                    </div>
                    <div class="fw-semibold small">{{ $kat->nama_kategori }}</div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- PRODUK POPULER --}}
<section class="py-5" style="background:#f8faf9;">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <h2 class="section-title mb-1">Alat Populer</h2>
                <p class="text-muted mb-0">Paling banyak disewa oleh pendaki</p>
            </div>
            <a href="{{ route('katalog.index') }}" class="btn btn-outline-brand px-4">
                Lihat Semua <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>
        <div class="row g-4">
            @forelse($alats as $alat)
            <div class="col-xl-2 col-lg-3 col-md-4 col-6">
                <div class="gear-card card h-100">
                    <div class="gear-img-wrapper">
                        @if($alat->gambar)
                            <img src="{{ Storage::url($alat->gambar) }}" alt="{{ $alat->nama_alat }}" loading="lazy">
                        @else
                            <div class="d-flex align-items-center justify-content-center h-100">
                                <i class="bi bi-image text-muted" style="font-size:3rem;"></i>
                            </div>
                        @endif
                        <span class="gear-badge kondisi-{{ Str::slug($alat->kondisi) }}">
                            {{ $alat->kondisi }}
                        </span>
                    </div>
                    <div class="card-body p-3 d-flex flex-column">
                        <div class="small text-muted mb-1">{{ $alat->kategori->nama_kategori ?? '-' }}</div>
                        <h6 class="fw-bold mb-2 lh-sm" style="font-size:0.9rem;">{{ $alat->nama_alat }}</h6>
                        <div class="mt-auto">
                            <div class="price-tag mb-2">Rp {{ number_format($alat->harga_per_hari, 0, ',', '.') }}<span class="text-muted fw-normal small">/hari</span></div>
                            <div class="d-flex align-items-center justify-content-between">
                                <span class="small text-muted">
                                    <i class="bi bi-box-seam me-1"></i>
                                    Stok: {{ $alat->stok_total }}
                                </span>
                                <a href="{{ route('katalog.show', $alat->slug) }}" class="btn btn-brand btn-sm">
                                    Sewa
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-5 text-muted">
                <i class="bi bi-box-seam fs-1 mb-3 d-block"></i>
                <p>Belum ada alat tersedia saat ini.</p>
            </div>
            @endforelse
        </div>
    </div>
</section>

{{-- PAKET SEWA --}}
@if($pakets->count() > 0)
<section class="py-5 bg-white">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <h2 class="section-title mb-1">Paket Sewa Hemat</h2>
                <p class="text-muted mb-0">Paket lengkap untuk pengalaman hiking yang lebih mudah</p>
            </div>
            <a href="{{ route('paket.index') }}" class="btn btn-outline-brand px-4">
                Lihat Semua <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>
        <div class="row g-4">
            @foreach($pakets as $paket)
            <div class="col-lg-4 col-md-6">
                <div class="paket-card card h-100">
                    @if($paket->gambar)
                        <img src="{{ Storage::url($paket->gambar) }}" class="paket-img" alt="{{ $paket->nama_paket }}" loading="lazy">
                    @else
                        <div class="paket-img bg-gradient d-flex align-items-center justify-content-center" style="background:linear-gradient(135deg,#173F35,#2d6a4f);">
                            <i class="bi bi-boxes text-white" style="font-size:4rem;opacity:0.5;"></i>
                        </div>
                    @endif
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h5 class="fw-bold mb-0" style="color:#173F35;">{{ $paket->nama_paket }}</h5>
                            <span class="badge" style="background:#e8f5e9;color:#173F35;font-size:0.7rem;">
                                <i class="bi bi-people me-1"></i>{{ $paket->kapasitas_orang }}P
                            </span>
                        </div>
                        <p class="text-muted small mb-3 lh-base">{{ Str::limit($paket->deskripsi, 100) }}</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="text-muted small">Mulai dari</div>
                                <div class="price-tag fs-5">Rp {{ number_format($paket->harga_paket_per_hari, 0, ',', '.') }}<span class="text-muted fw-normal small">/hari</span></div>
                            </div>
                            <a href="{{ route('paket.show', $paket->slug) }}" class="btn btn-brand px-3">
                                Detail <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- CARA SEWA --}}
<section class="py-5" style="background:linear-gradient(135deg,#173F35 0%,#1e5240 100%);">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold text-white mb-2" style="font-size:2rem;">Cara Menyewa</h2>
            <p class="text-white-50">Proses mudah dalam 4 langkah sederhana</p>
        </div>
        <div class="row g-4">
            @php $steps = [
                ['num'=>'1','icon'=>'bi-search','title'=>'Pilih Alat','desc'=>'Cari dan pilih peralatan hiking yang Anda butuhkan dari katalog kami.'],
                ['num'=>'2','icon'=>'bi-calendar-check','title'=>'Tentukan Tanggal','desc'=>'Pilih tanggal mulai dan selesai sewa sesuai jadwal pendakian Anda.'],
                ['num'=>'3','icon'=>'bi-credit-card','title'=>'Konfirmasi Booking','desc'=>'Lengkapi data sewa dan unggah dokumen jaminan yang diperlukan.'],
                ['num'=>'4','icon'=>'bi-bag-check','title'=>'Ambil & Gunakan','desc'=>'Ambil alat di toko atau terima via kurir. Nikmati petualangan Anda!'],
            ]; @endphp
            @foreach($steps as $step)
            <div class="col-lg-3 col-md-6">
                <div class="step-card">
                    <div class="step-num">{{ $step['num'] }}</div>
                    <i class="bi {{ $step['icon'] }} text-warning fs-2 mb-3 d-block"></i>
                    <h5 class="text-white fw-bold mb-2">{{ $step['title'] }}</h5>
                    <p class="text-white-50 small mb-0">{{ $step['desc'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
        <div class="text-center mt-5">
            <a href="{{ route('katalog.index') }}" class="btn btn-warning btn-lg px-5 fw-bold">
                <i class="bi bi-bag-plus me-2"></i> Mulai Sewa Sekarang
            </a>
        </div>
    </div>
</section>

{{-- FAQ --}}
<section class="py-5 bg-white">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="text-center mb-5">
                    <h2 class="section-title">Pertanyaan Umum</h2>
                    <p class="section-subtitle mb-0">Hal-hal yang sering ditanyakan pelanggan kami</p>
                </div>
                <div class="accordion accordion-flush" id="faqAccordion">
                    @php $faqs = [
                        ['q'=>'Apa saja dokumen yang diperlukan untuk menyewa?','a'=>'Anda perlu melampirkan foto KTP atau SIM sebagai dokumen jaminan. Dokumen ini hanya digunakan sebagai jaminan dan akan dikembalikan setelah alat dikembalikan.'],
                        ['q'=>'Bagaimana cara mengembalikan alat setelah digunakan?','a'=>'Alat dapat dikembalikan langsung ke toko kami sesuai jadwal. Pastikan alat dalam kondisi bersih dan lengkap. Tim kami akan memeriksa kondisi alat saat pengembalian.'],
                        ['q'=>'Apakah ada denda jika terlambat mengembalikan?','a'=>'Ya, ada denda keterlambatan sebesar Rp {{ number_format($setting->denda_keterlambatan_per_hari ?? 50000, 0, \',\', \'.\') }}/hari per item jika melewati batas waktu yang disepakati.'],
                        ['q'=>'Apakah bisa menyewa dalam jumlah banyak sekaligus?','a'=>'Tentu! Anda bisa menyewa beberapa jenis alat sekaligus dalam satu transaksi. Tersedia juga paket sewa yang lebih ekonomis untuk kebutuhan lengkap.'],
                        ['q'=>'Bagaimana jika alat yang saya butuhkan sedang tidak tersedia?','a'=>'Anda bisa menghubungi kami via WhatsApp untuk informasi ketersediaan atau waktu alat tersedia kembali. Kami akan berusaha membantu mencarikan solusi terbaik.'],
                    ]; @endphp
                    @foreach($faqs as $i => $faq)
                    <div class="accordion-item border-0 mb-2 rounded-3 shadow-sm">
                        <h2 class="accordion-header">
                            <button class="accordion-button {{ $i > 0 ? 'collapsed' : '' }} rounded-3 fw-semibold" type="button"
                                data-bs-toggle="collapse" data-bs-target="#faq{{ $i }}" style="background:{{ $i===0?'#e8f5e9':'white' }};">
                                <i class="bi bi-question-circle text-warning me-2"></i>
                                {{ $faq['q'] }}
                            </button>
                        </h2>
                        <div id="faq{{ $i }}" class="accordion-collapse collapse {{ $i===0?'show':'' }}" data-bs-parent="#faqAccordion">
                            <div class="accordion-body text-muted small pt-0">
                                {{ $faq['a'] }}
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="text-center mt-4">
                    <p class="text-muted small">Masih punya pertanyaan?
                        <a href="{{ route('kontak') }}" class="fw-semibold text-decoration-none" style="color:#173F35;">Hubungi kami</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

