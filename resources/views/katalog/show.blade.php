@extends('layouts.app')

@php
    $shopSettings = getShopSettings();
    $avail = getItemAvailability($alat->id_alat);
    $imgSrc = getProductImageUrl($alat);
@endphp

@section('content')
<!-- Breadcrumb -->
<div class="py-3 bg-light border-bottom">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 small">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Beranda</a></li>
                <li class="breadcrumb-item"><a href="{{ route('katalog.index') }}" class="text-decoration-none">Katalog</a></li>
                <li class="breadcrumb-item"><a href="{{ route('katalog.index', ['kategori' => $alat->kategori->slug]) }}" class="text-decoration-none">{{ $alat->kategori->nama_kategori }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $alat->nama_alat }}</li>
            </ol>
        </nav>
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

        <div class="row g-4 mb-5">
            <!-- Gambar Produk -->
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden p-2 bg-white mb-3">
                    <img src="{{ $imgSrc }}" alt="{{ $alat->nama_alat }}" class="img-fluid rounded-3 w-100" style="max-height: 440px; object-fit: cover;" id="mainImage">
                </div>

                <!-- Gallery Thumbnails -->
                @if($alat->gallery->count() > 0)
                    <div class="d-flex gap-2 mb-3 overflow-auto pb-2">
                        @foreach($alat->gallery as $photo)
                            @php
                                $thumbPath = ltrim(str_replace('\\', '/', $photo->gambar), '/');
                                $thumbSrc = null;

                                if (\Illuminate\Support\Facades\Storage::disk('public')->exists($thumbPath)) {
                                    $thumbSrc = \Illuminate\Support\Facades\Storage::disk('public')->url($thumbPath);
                                } elseif (is_file(public_path($thumbPath))) {
                                    $thumbSrc = asset($thumbPath);
                                } else {
                                    $uploadPath = 'uploads/barang/' . basename($thumbPath);
                                    if (is_file(public_path($uploadPath))) {
                                        $thumbSrc = asset($uploadPath);
                                    }
                                }
                            @endphp
                            @if($thumbSrc)
                                <button type="button"
                                        class="gallery-thumb rounded-3 border-2 flex-shrink-0 {{ $photo->urutan == 0 ? 'border-success' : 'border-light' }}"
                                        onclick="changeImage('{{ $thumbSrc }}')"
                                        style="width: 80px; height: 80px; padding: 2px; background: none; cursor: pointer; overflow: hidden;">
                                    <img src="{{ $thumbSrc }}" alt="{{ $alat->nama_alat }}" class="w-100 h-100 rounded-2" style="object-fit: cover;">
                                </button>
                            @endif
                        @endforeach
                    </div>
                @endif

                <div class="d-flex align-items-center gap-3 p-3 bg-white rounded-3 border">
                    <i class="bi bi-shield-check text-success fs-3"></i>
                    <div class="small">
                        <strong>Jaminan Kualitas & Kebersihan:</strong> Seluruh peralatan diperiksa frame, resleting, dan kainnya serta melalui proses pencucian higienis setelah setiap pemakaian.
                    </div>
                </div>
            </div>

            <!-- Detail & Form Estimasi -->
            <div class="col-lg-6">
                <div class="ps-lg-3">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <span class="badge bg-secondary">{{ $alat->kategori->nama_kategori }}</span>
                        <span class="badge bg-outline-dark border">{{ $alat->merk }}</span>
                        @if($alat->kondisi === 'Baik')
                            <span class="badge bg-success-subtle text-success border border-success"><i class="bi bi-check-circle me-1"></i> Kondisi: Baik</span>
                        @else
                            <span class="badge bg-warning-subtle text-warning border border-warning"><i class="bi bi-exclamation-circle me-1"></i> {{ $alat->kondisi }}</span>
                        @endif
                    </div>

                    <h2 class="fw-bold mb-2">{{ $alat->nama_alat }}</h2>
                    
                    <div class="d-flex align-items-baseline gap-2 mb-3">
                        <span class="fs-2 fw-bold text-success font-monospace">{{ formatRupiah($alat->harga_per_hari) }}</span>
                        <span class="text-muted">/ hari sewa</span>
                    </div>

                    <!-- Status Stok -->
                    <div class="p-3 bg-light rounded-3 mb-4 border">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <small class="text-muted d-block">Status Ketersediaan Fisik:</small>
                                <strong>Stok Total: {{ $alat->stok_total }} Unit</strong>
                            </div>
                            <div>
                                @if($avail['sisa_stok'] > 0)
                                    <span class="badge bg-success fs-6 px-3 py-2"><i class="bi bi-check2-circle me-1"></i> Tersedia ({{ $avail['sisa_stok'] }} Unit Siap)</span>
                                @else
                                    <span class="badge bg-danger fs-6 px-3 py-2"><i class="bi bi-x-circle me-1"></i> Stok Sedang Penuh</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Tab Deskripsi & Spesifikasi -->
                    <ul class="nav nav-tabs mb-3" id="productTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active fw-semibold" id="desc-tab" data-bs-toggle="tab" data-bs-target="#desc-pane" type="button" role="tab">Deskripsi</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link fw-semibold" id="spec-tab" data-bs-toggle="tab" data-bs-target="#spec-pane" type="button" role="tab">Spesifikasi Teknis</button>
                        </li>
                    </ul>
                    <div class="tab-content mb-4" id="productTabContent">
                        <div class="tab-pane fade show active small text-muted" id="desc-pane" role="tabpanel">
                            {!! nl2br(e($alat->deskripsi)) !!}
                        </div>
                        <div class="tab-pane fade small" id="spec-pane" role="tabpanel">
                            <div class="bg-light p-3 rounded-3 border">
                                <pre class="mb-0 text-dark" style="font-family: inherit; white-space: pre-line;">{{ $alat->spesifikasi }}</pre>
                            </div>
                        </div>
                    </div>

                    <!-- Box Hitung Estimasi Cepat & Booking -->
                    <div class="card border-0 shadow-sm rounded-4 p-4" style="background-color: var(--off-white); border: 1px solid var(--border-color) !important;">
                        <h6 class="fw-bold mb-3"><i class="bi bi-calculator-fill text-warning me-2"></i>Kalkulator Estimasi Biaya Sewa</h6>
                        
                        <input type="hidden" id="harga_per_hari_raw" value="{{ $alat->harga_per_hari }}">
                        <input type="hidden" id="flat_delivery_fee" value="{{ $shopSettings['biaya_pengiriman_flat'] }}">

                        <div class="row g-2 mb-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Tanggal Mulai Sewa</label>
                                <input type="date" id="tgl_sewa" class="form-control form-control-sm" value="{{ date('Y-m-d') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Tanggal Pengembalian</label>
                                <input type="date" id="tgl_kembali" class="form-control form-control-sm" value="{{ date('Y-m-d', strtotime('+2 days')) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Jumlah Unit</label>
                                <input type="number" id="jumlah_unit" class="form-control form-control-sm" min="1" max="{{ max(1, $avail['sisa_stok']) }}" value="1">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Metode Pengambilan</label>
                                <select id="metode_pengambilan" class="form-select form-select-sm">
                                    <option value="Ambil di Toko">Ambil di Toko (Gratis)</option>
                                    <option value="Kirim via Kurir">Kirim Kurir (+{{ formatRupiah($shopSettings['biaya_pengiriman_flat']) }})</option>
                                </select>
                            </div>
                        </div>

                        <div class="p-3 bg-white rounded-3 border mb-3">
                            <div class="d-flex justify-content-between small text-muted mb-1">
                                <span>Durasi Sewa:</span>
                                <strong id="calc_duration" class="text-dark">2 Hari</strong>
                            </div>
                            <div class="d-flex justify-content-between small text-muted mb-1">
                                <span>Subtotal Sewa Alat:</span>
                                <span id="calc_subtotal" class="font-monospace text-dark">{{ formatRupiah($alat->harga_per_hari * 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between small text-muted mb-2">
                                <span>Biaya Pengantaran:</span>
                                <span id="calc_delivery_fee" class="font-monospace text-dark">Rp 0</span>
                            </div>
                            <div class="d-flex justify-content-between border-top pt-2">
                                <strong class="text-dark">Total Estimasi:</strong>
                                <strong id="calc_total" class="fs-5 text-success font-monospace">{{ formatRupiah($alat->harga_per_hari * 2) }}</strong>
                            </div>
                        </div>

                        @if($avail['sisa_stok'] > 0)
                            <a href="{{ route('booking.create', ['alat_id' => $alat->id_alat]) }}" class="btn btn-brand w-100 py-2 fs-6 fw-bold">
                                <i class="bi bi-calendar-check-fill me-2"></i> Lanjutkan Pemesanan / Sewa Sekarang
                            </a>
                        @else
                            <button class="btn btn-secondary w-100 py-2" disabled>
                                <i class="bi bi-x-circle me-1"></i> Stok Sedang Kosong / Tidak Tersedia
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Perlengkapan Terkait -->
        @if($related->count() > 0)
            <div class="pt-4 border-top">
                <h4 class="fw-bold mb-4">Peralatan Lain Dalam Kategori Ini</h4>
                <div class="row g-4">
                    @foreach($related as $rel)
                        @php
                            $relAvail = getItemAvailability($rel->id_alat);
                            $relImg = getProductImageUrl($rel);
                        @endphp
                        <div class="col-md-4">
                            <div class="gear-card">
                                <div class="gear-img-wrapper" style="height: 180px;">
                                    <img src="{{ $relImg }}" alt="{{ $rel->nama_alat }}">
                                </div>
                                <div class="p-3">
                                    <div class="text-muted small fw-semibold text-uppercase">{{ $rel->merk }}</div>
                                    <h6 class="fw-bold mb-2">
                                        <a href="{{ route('katalog.show', $rel->slug) }}" class="text-dark text-decoration-none">
                                            {{ $rel->nama_alat }}
                                        </a>
                                    </h6>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="text-success fw-bold">{{ formatRupiah($rel->harga_per_hari) }}/hari</div>
                                        <a href="{{ route('katalog.show', $rel->slug) }}" class="btn btn-outline-forest btn-sm">Lihat</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

    </div>
</main>

@push('scripts')
<script>
// Kalkulator Biaya Sewa Real-Time
function hitungEstimasi() {
    const hargaPerHari = parseFloat(document.getElementById('harga_per_hari_raw').value);
    const flatDelivery = parseFloat(document.getElementById('flat_delivery_fee').value);
    const tglSewa = document.getElementById('tgl_sewa').value;
    const tglKembali = document.getElementById('tgl_kembali').value;
    const jumlahUnit = parseInt(document.getElementById('jumlah_unit').value) || 1;
    const metodePengambilan = document.getElementById('metode_pengambilan').value;

    // Hitung durasi
    const startDate = new Date(tglSewa);
    const endDate = new Date(tglKembali);
    const diffTime = Math.abs(endDate - startDate);
    const durasi = Math.max(1, Math.ceil(diffTime / (1000 * 60 * 60 * 24)));

    // Hitung subtotal
    const subtotal = hargaPerHari * durasi * jumlahUnit;
    
    // Biaya kirim
    const biayaKirim = metodePengambilan.includes('Kurir') ? flatDelivery : 0;
    
    // Total
    const total = subtotal + biayaKirim;

    // Update UI
    document.getElementById('calc_duration').textContent = durasi + ' Hari';
    document.getElementById('calc_subtotal').textContent = formatRupiah(subtotal);
    document.getElementById('calc_delivery_fee').textContent = formatRupiah(biayaKirim);
    document.getElementById('calc_total').textContent = formatRupiah(total);
}

function formatRupiah(amount) {
    return 'Rp ' + new Intl.NumberFormat('id-ID').format(Math.round(amount));
}

// Event listeners
document.getElementById('tgl_sewa').addEventListener('change', hitungEstimasi);
document.getElementById('tgl_kembali').addEventListener('change', hitungEstimasi);
document.getElementById('jumlah_unit').addEventListener('input', hitungEstimasi);
document.getElementById('metode_pengambilan').addEventListener('change', hitungEstimasi);

// Initial calculation
hitungEstimasi();

// Gallery image change
function changeImage(imageSrc) {
    document.getElementById('mainImage').src = imageSrc;
}
</script>
@endpush
@endsection
