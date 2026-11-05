@php
    $shopSettings = getShopSettings();
@endphp

<footer class="py-5">
    <div class="container">
        <div class="row g-4 mb-4">
            <div class="col-lg-4 col-md-6">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <i class="bi bi-compass text-warning fs-2"></i>
                    <span class="fs-4 fw-bold text-white font-monospace">HIKETRIP RENTAL</span>
                </div>
                <p class="small text-white-50 mb-3">
                    {{ $shopSettings['tagline'] }}. Penyedia layanan rental peralatan hiking, camping, dan pendakian gunung berkualitas teruji, bersih, dan siap pakai untuk petualangan aman Anda.
                </p>
                <div class="d-flex gap-2">
                    <span class="badge bg-secondary p-2"><i class="bi bi-shield-check me-1"></i> Standar Uji LSP RPL</span>
                    <span class="badge bg-secondary p-2"><i class="bi bi-patch-check me-1"></i> Higienis & Terawat</span>
                </div>
            </div>

            <div class="col-lg-2 col-md-6">
                <h6 class="text-white fw-bold mb-3">Navigasi</h6>
                <ul class="list-unstyled small mb-0 d-flex flex-column gap-2">
                    <li><a href="{{ route('home') }}"><i class="bi bi-chevron-right text-warning me-1"></i> Beranda</a></li>
                    <li><a href="{{ route('katalog.index') }}"><i class="bi bi-chevron-right text-warning me-1"></i> Katalog Peralatan</a></li>
                    <li><a href="{{ route('paket.index') }}"><i class="bi bi-chevron-right text-warning me-1"></i> Paket Camping Hemat</a></li>
                    <li><a href="{{ route('tentang') }}"><i class="bi bi-chevron-right text-warning me-1"></i> Tentang Kami</a></li>
                    <li><a href="{{ route('kontak') }}"><i class="bi bi-chevron-right text-warning me-1"></i> Hubungi Toko</a></li>
                </ul>
            </div>

            <div class="col-lg-3 col-md-6">
                <h6 class="text-white fw-bold mb-3">Kategori Populer</h6>
                <ul class="list-unstyled small mb-0 d-flex flex-column gap-2">
                    <li><a href="{{ route('katalog.index', ['kategori' => 'tenda']) }}"><i class="bi bi-shield-shaded me-1"></i> Tenda Dome Waterproof</a></li>
                    <li><a href="{{ route('katalog.index', ['kategori' => 'carrier']) }}"><i class="bi bi-backpack me-1"></i> Carrier / Tas Gunung</a></li>
                    <li><a href="{{ route('katalog.index', ['kategori' => 'sleeping-gear']) }}"><i class="bi bi-moon-stars me-1"></i> Sleeping Bag & Matras</a></li>
                    <li><a href="{{ route('katalog.index', ['kategori' => 'cooking-gear']) }}"><i class="bi bi-fire me-1"></i> Cooking Set & Kompor</a></li>
                    <li><a href="{{ route('katalog.index', ['kategori' => 'lighting-tools']) }}"><i class="bi bi-lightbulb me-1"></i> Headlamp & Lighting</a></li>
                </ul>
            </div>

            <div class="col-lg-3 col-md-6">
                <h6 class="text-white fw-bold mb-3">Informasi Kontak</h6>
                <ul class="list-unstyled small mb-0 d-flex flex-column gap-2 text-white-50">
                    <li class="d-flex align-items-start gap-2">
                        <i class="bi bi-geo-alt-fill text-warning mt-1"></i>
                        <span>{!! nl2br(e($shopSettings['alamat'])) !!}</span>
                    </li>
                    <li class="d-flex align-items-center gap-2">
                        <i class="bi bi-whatsapp text-warning"></i>
                        <span>{{ $shopSettings['whatsapp'] }}</span>
                    </li>
                    <li class="d-flex align-items-center gap-2">
                        <i class="bi bi-clock-fill text-warning"></i>
                        <span>{{ $shopSettings['jam_operasional'] }}</span>
                    </li>
                    <li class="d-flex align-items-center gap-2">
                        <i class="bi bi-envelope-fill text-warning"></i>
                        <span>{{ $shopSettings['email'] }}</span>
                    </li>
                </ul>
            </div>
        </div>

        <hr class="border-secondary opacity-25">

        <div class="row align-items-center small text-white-50">
            <div class="col-md-6 text-center text-md-start">
                &copy; {{ date('Y') }} <strong>{{ $shopSettings['nama_toko'] }}</strong>. Hak Cipta Dilindungi Undang-Undang.
            </div>
            <div class="col-md-6 text-center text-md-end mt-2 mt-md-0">
                Proyek Uji Kompetensi Keahlian (UKK) Rekayasa Perangkat Lunak (LSP RPL).
            </div>
        </div>
    </div>
</footer>
