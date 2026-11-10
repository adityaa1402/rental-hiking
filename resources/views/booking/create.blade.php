@extends('layouts.app')

@php
    $shopSettings = getShopSettings();
@endphp

@section('content')
<!-- Header Banner -->
<div class="py-4" style="background-color: var(--forest-green); color: #ffffff;">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-1">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white-50 text-decoration-none">Beranda</a></li>
                <li class="breadcrumb-item active text-white" aria-current="page">Form Booking</li>
            </ol>
        </nav>
        <h2 class="fw-bold mb-0">Formulir Penyewaan Peralatan</h2>
        <p class="text-white-50 small mb-0">Lengkapi data pemesanan untuk proses verifikasi oleh admin.</p>
    </div>
</div>

<main class="py-5">
    <div class="container">
        @if($errors->any())
            <div class="alert alert-danger" role="alert">
                <strong>Terdapat Kesalahan:</strong>
                <ul class="mb-0 mt-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('booking.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="row g-4">
                <!-- Detail Item/Paket yang Dipilih -->
                <div class="col-lg-4 order-lg-2">
                    <div class="card border-0 shadow-sm rounded-4 p-4 sticky-panel">
                        <h5 class="fw-bold mb-3"><i class="bi bi-bag-check text-warning me-2"></i>Item Sewa</h5>

                        @if($selectedItem)
                            <input type="hidden" name="alat_id" value="{{ $selectedItem->id_alat }}">
                            <div class="d-flex gap-3 mb-3 pb-3 border-bottom">
                                @php($imgSrc = getProductImageUrl($selectedItem))
                                <img src="{{ $imgSrc }}" alt="{{ $selectedItem->nama_alat }}" class="rounded" style="width: 80px; height: 80px; object-fit: cover;">
                                <div>
                                    <div class="text-muted small">{{ $selectedItem->kategori->nama_kategori }}</div>
                                    <h6 class="fw-bold mb-1">{{ $selectedItem->nama_alat }}</h6>
                                    <div class="text-success fw-bold">{{ formatRupiah($selectedItem->harga_per_hari) }}/hari</div>
                                </div>
                            </div>
                        @endif

                        @if($selectedPackage)
                            <input type="hidden" name="paket_id" value="{{ $selectedPackage->id_paket }}">
                            <div class="mb-3">
                                <span class="badge bg-warning text-dark mb-2"><i class="bi bi-box-seam me-1"></i> Paket Sewa</span>
                                <h6 class="fw-bold">{{ $selectedPackage->nama_paket }}</h6>
                                <div class="text-success fw-bold mb-3">{{ formatRupiah($selectedPackage->harga_paket_per_hari) }}/hari</div>
                                
                                <div class="small text-muted">
                                    <strong>Komponen:</strong>
                                    <ul class="mt-2 ps-3">
                                        @foreach($packageItems as $item)
                                            <li>{{ $item->jumlah_unit }}x {{ $item->alatHiking->nama_alat }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endif

                        <div class="alert alert-info small mb-0">
                            <i class="bi bi-info-circle me-1"></i>
                            Status pengajuan akan ditampilkan di dashboard Anda setelah submit.
                        </div>
                    </div>
                </div>

                <!-- Form Booking -->
                <div class="col-lg-8 order-lg-1">
                    <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                        <h5 class="fw-bold mb-3">Informasi Penyewaan</h5>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Tanggal Mulai Sewa <span class="text-danger">*</span></label>
                                <input type="date" name="tgl_sewa" id="tgl_sewa" class="form-control" value="{{ old('tgl_sewa', date('Y-m-d')) }}" min="{{ date('Y-m-d') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Tanggal Pengembalian <span class="text-danger">*</span></label>
                                <input type="date" name="tgl_kembali" id="tgl_kembali" class="form-control" value="{{ old('tgl_kembali', date('Y-m-d', strtotime('+2 days'))) }}" min="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Jumlah Unit <span class="text-danger">*</span></label>
                                <input type="number" name="jumlah_unit" class="form-control" min="1" max="10" value="{{ old('jumlah_unit', 1) }}" required>
                                <small class="text-muted">Maksimal 10 unit per transaksi</small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Metode Pengambilan <span class="text-danger">*</span></label>
                                <select name="metode_pengambilan" id="metode_pengambilan" class="form-select" required>
                                    <option value="Ambil di Toko" {{ old('metode_pengambilan') == 'Ambil di Toko' ? 'selected' : '' }}>Ambil di Toko (Gratis)</option>
                                    <option value="Kirim via Kurir" {{ old('metode_pengambilan') == 'Kirim via Kurir' ? 'selected' : '' }}>Kirim via Kurir (+{{ formatRupiah($shopSettings['biaya_pengiriman_flat']) }})</option>
                                </select>
                            </div>
                            <div class="col-12" id="alamat_pengiriman_wrapper" style="display: {{ old('metode_pengambilan') == 'Kirim via Kurir' ? 'block' : 'none' }};">
                                <label class="form-label fw-semibold">Alamat Pengiriman <span class="text-danger">*</span></label>
                                <textarea name="alamat_pengiriman" id="alamat_pengiriman" class="form-control" rows="2" placeholder="Jl. Nama Jalan, Kota, Kode Pos">{{ old('alamat_pengiriman', auth()->user()->alamat) }}</textarea>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Catatan Tambahan (Opsional)</label>
                                <textarea name="catatan_customer" class="form-control" rows="2" placeholder="Contoh: Mohon dikemas dengan bubble wrap, akan digunakan untuk mendaki Gunung Merbabu">{{ old('catatan_customer') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                        <h5 class="fw-bold mb-3">Upload Dokumen Jaminan</h5>
                        <p class="text-muted small mb-3">Wajib upload foto KTP/SIM yang masih berlaku sebagai jaminan penyewaan.</p>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Tipe Dokumen <span class="text-danger">*</span></label>
                                <select name="tipe_dokumen" class="form-select" required>
                                    <option value="KTP" {{ old('tipe_dokumen') == 'KTP' ? 'selected' : '' }}>KTP (Kartu Tanda Penduduk)</option>
                                    <option value="SIM" {{ old('tipe_dokumen') == 'SIM' ? 'selected' : '' }}>SIM (Surat Izin Mengemudi)</option>
                                    <option value="Paspor" {{ old('tipe_dokumen') == 'Paspor' ? 'selected' : '' }}>Paspor</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Upload Foto/Scan <span class="text-danger">*</span></label>
                                <input type="file" name="dokumen_jaminan" class="form-control" accept="image/jpeg,image/png,image/webp,application/pdf" required>
                                <small class="text-muted">Format: JPG, PNG, PDF. Maks: 3MB</small>
                            </div>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                        <h5 class="fw-bold mb-3">Syarat & Ketentuan</h5>
                        <div class="form-check">
                            <input type="checkbox" name="setuju_syarat" class="form-check-input" id="setuju" required {{ old('setuju_syarat') ? 'checked' : '' }}>
                            <label class="form-check-label small" for="setuju">
                                Saya telah membaca dan menyetujui <a href="#" data-bs-toggle="modal" data-bs-target="#modalSyarat">syarat & ketentuan</a> penyewaan HIKETRIP RENTAL. Saya bertanggung jawab atas kondisi barang selama masa sewa.
                            </label>
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-brand btn-lg py-3 fw-bold">
                            <i class="bi bi-send-fill me-2"></i> Ajukan Penyewaan Sekarang
                        </button>
                        <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-1"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</main>

<!-- Modal Syarat & Ketentuan -->
<div class="modal fade" id="modalSyarat" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Syarat & Ketentuan Penyewaan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body small">
                <ol>
                    <li>Penyewa wajib membawa dan menunjukkan kartu identitas asli (KTP/SIM) saat pengambilan barang.</li>
                    <li>Barang harus dikembalikan sesuai jadwal yang ditentukan paling lambat pukul 21:00 WIB.</li>
                    <li>Keterlambatan pengembalian dikenakan denda {{ formatRupiah($shopSettings['denda_keterlambatan_per_hari']) }}/hari.</li>
                    <li>Penyewa bertanggung jawab penuh atas kerusakan atau kehilangan barang selama masa sewa.</li>
                    <li>Peralatan yang dikembalikan harus dalam kondisi bersih dan kering.</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const metodeSelect = document.getElementById('metode_pengambilan');
    const alamatWrapper = document.getElementById('alamat_pengiriman_wrapper');
    const alamatField = document.getElementById('alamat_pengiriman');
    const tanggalSewa = document.getElementById('tgl_sewa');
    const tanggalKembali = document.getElementById('tgl_kembali');

    function updateTanggalKembaliMinimum() {
        const [year, month, day] = tanggalSewa.value.split('-').map(Number);
        const minimum = new Date(year, month - 1, day + 1);
        const minimumValue = [
            minimum.getFullYear(),
            String(minimum.getMonth() + 1).padStart(2, '0'),
            String(minimum.getDate()).padStart(2, '0')
        ].join('-');

        tanggalKembali.min = minimumValue;
        if (tanggalKembali.value < minimumValue) {
            tanggalKembali.value = minimumValue;
        }
    }

    // Function untuk handle show/hide alamat pengiriman
    function toggleAlamatField() {
        if (metodeSelect.value === 'Kirim via Kurir') {
            alamatWrapper.style.display = 'block';
            alamatField.setAttribute('required', 'required');
        } else {
            alamatWrapper.style.display = 'none';
            alamatField.removeAttribute('required');
            alamatField.value = '';
        }
    }

    // Trigger on change
    metodeSelect.addEventListener('change', toggleAlamatField);
    tanggalSewa.addEventListener('change', updateTanggalKembaliMinimum);

    // Initial check on page load
    updateTanggalKembaliMinimum();
    toggleAlamatField();
});
</script>
@endsection
