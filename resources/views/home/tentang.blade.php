@extends('layouts.app')

@section('title', 'Tentang Kami')

@section('content')

{{-- Page Header --}}
<div class="py-5" style="background:linear-gradient(135deg,#173F35,#2d6a4f);">
    <div class="container text-center text-white py-3">
        <h1 class="fw-bold mb-2"><i class="bi bi-info-circle me-2 text-warning"></i>Tentang Kami</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-center mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-warning">Beranda</a></li>
                <li class="breadcrumb-item active text-white-50">Tentang Kami</li>
            </ol>
        </nav>
    </div>
</div>

{{-- Main Content --}}
<section class="py-5 bg-white">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <div class="bg-light rounded-4 d-flex align-items-center justify-content-center" style="height:380px;">
                    <div class="text-center">
                        <i class="bi bi-mountains" style="font-size:8rem;color:#173F35;opacity:0.3;"></i>
                        <div class="fw-bold fs-4" style="color:#173F35;">HIKETRIP RENTAL</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <span class="badge mb-3 px-3 py-2" style="background:#e8f5e9;color:#173F35;font-size:0.85rem;">
                    <i class="bi bi-compass me-1"></i> Tentang Kami
                </span>
                <h2 class="fw-bold mb-3" style="color:#173F35;font-size:2rem;">
                    {{ $setting->nama_toko ?? 'HIKETRIP RENTAL' }}
                </h2>
                <p class="text-muted mb-3 lh-lg">
                    {{ $setting->tagline ?? '' }}. Kami adalah platform rental peralatan hiking yang hadir untuk memudahkan setiap pendaki mendapatkan perlengkapan berkualitas tanpa harus membelinya.
                </p>
                <p class="text-muted mb-4 lh-lg">
                    Sejak berdiri, kami telah melayani ratusan pendaki dari berbagai daerah. Dengan koleksi lebih dari 50 jenis peralatan hiking, kami siap memenuhi kebutuhan petualangan Anda mulai dari tenda, carrier, sleeping bag, hingga perlengkapan masak di alam terbuka.
                </p>
                <div class="row g-3">
                    <div class="col-6">
                        <div class="d-flex align-items-center gap-3 p-3 rounded-3" style="background:#e8f5e9;">
                            <i class="bi bi-award-fill fs-3" style="color:#173F35;"></i>
                            <div>
                                <div class="fw-bold small" style="color:#173F35;">Terpercaya</div>
                                <div class="text-muted" style="font-size:0.75rem;">100+ pelanggan puas</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex align-items-center gap-3 p-3 rounded-3" style="background:#e8f5e9;">
                            <i class="bi bi-tools fs-3" style="color:#173F35;"></i>
                            <div>
                                <div class="fw-bold small" style="color:#173F35;">Berkualitas</div>
                                <div class="text-muted" style="font-size:0.75rem;">Dicek sebelum disewa</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Values --}}
<section class="py-5" style="background:#f8faf9;">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold" style="color:#173F35;font-size:2rem;">Nilai-Nilai Kami</h2>
            <p class="text-muted">Komitmen yang selalu kami jaga untuk pelanggan</p>
        </div>
        <div class="row g-4">
            @php $values = [
                ['icon'=>'bi-shield-check','title'=>'Keamanan','desc'=>'Setiap alat melewati pengecekan ketat sebelum disewakan. Keselamatan pendaki adalah prioritas utama kami.'],
                ['icon'=>'bi-stars','title'=>'Kualitas','desc'=>'Koleksi alat hiking pilihan dari merek-merek ternama. Semua dalam kondisi baik dan terawat.'],
                ['icon'=>'bi-heart','title'=>'Kepercayaan','desc'=>'Kami menjaga kepercayaan setiap pelanggan dengan layanan transparan dan harga yang jujur.'],
                ['icon'=>'bi-leaf','title'=>'Eco-Friendly','desc'=>'Mendukung gaya hidup berkelanjutan dengan konsep berbagi perlengkapan hiking antar pendaki.'],
            ]; @endphp
            @foreach($values as $val)
            <div class="col-md-6 col-lg-3">
                <div class="card border-0 rounded-4 p-4 h-100 shadow-sm text-center hover-lift">
                    <div class="rounded-circle mx-auto mb-4 d-flex align-items-center justify-content-center"
                        style="width:70px;height:70px;background:linear-gradient(135deg,#173F35,#2d6a4f);">
                        <i class="bi {{ $val['icon'] }} text-white fs-3"></i>
                    </div>
                    <h5 class="fw-bold mb-2" style="color:#173F35;">{{ $val['title'] }}</h5>
                    <p class="text-muted small mb-0 lh-lg">{{ $val['desc'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Stats --}}
<section class="py-5" style="background:linear-gradient(135deg,#173F35,#2d6a4f);">
    <div class="container">
        <div class="row g-4 text-center text-white">
            @php $stats = [
                ['num'=>'50+','label'=>'Alat Tersedia','icon'=>'bi-box-seam'],
                ['num'=>'100+','label'=>'Pelanggan Aktif','icon'=>'bi-people'],
                ['num'=>'500+','label'=>'Transaksi Sukses','icon'=>'bi-receipt'],
                ['num'=>'5★','label'=>'Rating Layanan','icon'=>'bi-star-fill'],
            ]; @endphp
            @foreach($stats as $stat)
            <div class="col-md-3 col-6">
                <i class="bi {{ $stat['icon'] }} text-warning fs-2 mb-2 d-block"></i>
                <div class="display-5 fw-bold text-warning">{{ $stat['num'] }}</div>
                <div class="text-white-50 small">{{ $stat['label'] }}</div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- CTA --}}
<section class="py-5 bg-white text-center">
    <div class="container">
        <div class="col-lg-6 mx-auto">
            <i class="bi bi-compass text-warning mb-3 d-block" style="font-size:3rem;"></i>
            <h3 class="fw-bold mb-3" style="color:#173F35;">Siap Memulai Petualangan?</h3>
            <p class="text-muted mb-4">Jelajahi koleksi peralatan hiking kami dan temukan alat yang tepat untuk petualangan Anda.</p>
            <div class="d-flex gap-3 justify-content-center">
                <a href="{{ route('katalog.index') }}" class="btn btn-brand px-4 py-2">
                    <i class="bi bi-grid me-1"></i> Lihat Katalog
                </a>
                <a href="{{ route('kontak') }}" class="btn btn-outline-secondary px-4 py-2">
                    <i class="bi bi-chat-dots me-1"></i> Hubungi Kami
                </a>
            </div>
        </div>
    </div>
</section>

@endsection

