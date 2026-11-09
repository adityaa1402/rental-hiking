@extends('layouts.app')

@php
    $isPickupAtStore = $transaksi->metode_pengambilan === 'Ambil di Toko';
    $metodeTersimpan = $pembayaran?->metode_pembayaran;
    if (!$isPickupAtStore && $metodeTersimpan === 'Tunai di Toko') {
        $metodeTersimpan = null;
    }
    $metodeTerpilih = old('metode_pembayaran', $metodeTersimpan);
    $buktiWajibAwal = in_array($metodeTerpilih, ['Transfer Bank', 'E-Wallet'], true);
@endphp

@section('content')
<!-- Header Banner -->
<div class="py-4" style="background-color: var(--forest-green); color: #ffffff;">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-1">
                <li class="breadcrumb-item"><a href="{{ route('customer.dashboard') }}" class="text-white-50 text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('customer.transaksi.index') }}" class="text-white-50 text-decoration-none">Transaksi</a></li>
                <li class="breadcrumb-item"><a href="{{ route('customer.transaksi.show', $transaksi->id_transaksi) }}" class="text-white-50 text-decoration-none">Detail</a></li>
                <li class="breadcrumb-item active text-white" aria-current="page">Pembayaran</li>
            </ol>
        </nav>
        <h2 class="fw-bold mb-0">Konfirmasi Pembayaran</h2>
        <p class="text-white-50 small mb-0">
            @if($isPickupAtStore)
                Bayar online dengan bukti transfer, atau pilih tunai saat mengambil barang di toko.
            @else
                Pesanan kurir wajib dibayar online melalui transfer bank atau e-wallet.
            @endif
        </p>
    </div>
</div>

<main class="py-5">
    <div class="container">
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong><i class="bi bi-exclamation-circle me-2"></i>Terdapat Kesalahan:</strong>
                <ul class="mb-0 mt-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row g-4">
            <!-- Transaksi Summary (Sticky) -->
            <div class="col-lg-4 order-lg-2">
                <div class="card border-0 shadow-sm rounded-4 p-4 sticky-top" style="top: 90px;">
                    <h5 class="fw-bold mb-3"><i class="bi bi-receipt text-success me-2"></i>Detail Transaksi</h5>

                    <!-- Kode Transaksi -->
                    <div class="mb-3 pb-3 border-bottom">
                        <small class="text-muted d-block">Kode Transaksi</small>
                        <div class="fs-6 fw-bold text-success">{{ $transaksi->kode_transaksi }}</div>
                    </div>

                    <!-- Items Summary -->
                    <div class="mb-3 pb-3 border-bottom">
                        <small class="text-muted d-block mb-2">Item Disewa</small>
                        @foreach($transaksi->detailTransaksi as $item)
                            <div class="small mb-1">
                                <strong>{{ $item->jumlah_unit }}x</strong> {{ $item->alatHiking->nama_alat }}
                            </div>
                        @endforeach
                    </div>

                    <!-- Periode Sewa -->
                    <div class="mb-3 pb-3 border-bottom">
                        <small class="text-muted d-block">Periode Sewa</small>
                        <div class="small">
                            <i class="bi bi-calendar-event text-muted me-1"></i>
                            {{ $transaksi->tgl_sewa->format('d M Y') }} - {{ $transaksi->tgl_kembali->format('d M Y') }}
                            <br>
                            <small class="text-muted">({{ $transaksi->total_hari }} hari)</small>
                        </div>
                    </div>

                    <!-- Rincian Biaya -->
                    <div class="mb-3 pb-3 border-bottom">
                        <small class="text-muted d-block mb-2">Rincian Biaya</small>
                        <div class="d-flex justify-content-between small mb-1">
                            <span>Subtotal:</span>
                            <strong>{{ formatRupiah($transaksi->subtotal) }}</strong>
                        </div>
                        @if($transaksi->biaya_pengiriman > 0)
                            <div class="d-flex justify-content-between small mb-1">
                                <span>Biaya Pengiriman:</span>
                                <strong>{{ formatRupiah($transaksi->biaya_pengiriman) }}</strong>
                            </div>
                        @endif
                        @if($transaksi->diskon > 0)
                            <div class="d-flex justify-content-between small mb-1 text-success">
                                <span>Diskon:</span>
                                <strong>-{{ formatRupiah($transaksi->diskon) }}</strong>
                            </div>
                        @endif
                    </div>

                    <!-- Total -->
                    <div class="p-3 rounded-3" style="background-color: var(--forest-green); color: white;">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-bold">Total Bayar:</span>
                            <span class="fs-5 fw-bold">{{ formatRupiah($transaksi->total_harga) }}</span>
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="mt-3">
                        @if($pembayaran && $pembayaran->status_pembayaran === 'Pending')
                            @if($pembayaran->metode_pembayaran === 'Tunai di Toko')
                                <div class="alert alert-info small mb-0">
                                    <i class="bi bi-shop me-1"></i> Pembayaran dicatat. Silakan lunasi saat mengambil barang di toko.
                                </div>
                            @else
                                <div class="alert alert-warning small mb-0">
                                    <i class="bi bi-clock-history me-1"></i> Menunggu verifikasi bukti pembayaran online
                                </div>
                            @endif
                        @elseif($pembayaran && $pembayaran->status_pembayaran === 'Confirmed')
                            <div class="alert alert-success small mb-0">
                                <i class="bi bi-check-circle me-1"></i> Pembayaran dikonfirmasi
                            </div>
                        @elseif($pembayaran && $pembayaran->status_pembayaran === 'Rejected')
                            <div class="alert alert-danger small mb-0">
                                <i class="bi bi-x-circle me-1"></i> Pembayaran ditolak
                                @if($pembayaran->catatan_admin)
                                    <div class="small mt-2">
                                        <strong>Alasan:</strong> {{ $pembayaran->catatan_admin }}
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Payment Form -->
            <div class="col-lg-8 order-lg-1">
                <!-- Metode Pembayaran Info -->
                <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                    <h5 class="fw-bold mb-3"><i class="bi bi-bank2 text-primary me-2"></i>Metode Pembayaran Tersedia</h5>
                    
                    <div class="row g-3">
                        <!-- Transfer Bank -->
                        <div class="col-md-6">
                            <div class="card border-2 rounded-3 p-3" style="border-color: #e9ecef;">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="rounded-circle p-2" style="background-color: #f8f9fa;">
                                        <i class="bi bi-credit-card fs-5 text-primary"></i>
                                    </div>
                                    <h6 class="mb-0 ms-2 fw-bold">Transfer Bank</h6>
                                </div>
                                <small class="text-muted">BCA: 1234567890<br>Mandiri: 0987654321<br>BNI: 1122334455</small>
                                <small class="d-block mt-2 text-muted">Atas Nama: PT HIKETRIP RENTAL</small>
                            </div>
                        </div>

                        <!-- E-Wallet -->
                        <div class="col-md-6">
                            <div class="card border-2 rounded-3 p-3" style="border-color: #e9ecef;">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="rounded-circle p-2" style="background-color: #f8f9fa;">
                                        <i class="bi bi-wallet2 fs-5 text-success"></i>
                                    </div>
                                    <h6 class="mb-0 ms-2 fw-bold">E-Wallet</h6>
                                </div>
                                <small class="text-muted">GCash<br>OVO<br>Dana</small>
                                <small class="d-block mt-2 text-muted">Hubungi admin untuk nomor tujuan</small>
                            </div>
                        </div>

                        @if($isPickupAtStore)
                            <!-- Tunai -->
                            <div class="col-md-6">
                                <div class="card border-2 rounded-3 p-3" style="border-color: #e9ecef;">
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="rounded-circle p-2" style="background-color: #f8f9fa;">
                                            <i class="bi bi-cash-coin fs-5 text-warning"></i>
                                        </div>
                                        <h6 class="mb-0 ms-2 fw-bold">Tunai di Toko</h6>
                                    </div>
                                    <small class="text-muted">Bayar langsung saat mengambil barang di toko</small>
                                    <small class="d-block mt-2 text-muted">Alamat: {{ $setting['alamat'] ?? 'HIKETRIP RENTAL' }}</small>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Payment Form -->
                <div class="card border-0 shadow-sm rounded-4 p-4">
                    <h5 class="fw-bold mb-3"><i class="bi bi-wallet2 text-primary me-2"></i>Pilih Pembayaran</h5>

                    <form action="{{ route('customer.payment.store', $transaksi->id_transaksi) }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- Metode Pembayaran -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Metode Pembayaran <span class="text-danger">*</span></label>
                            <select name="metode_pembayaran" class="form-select form-select-lg" required>
                                <option value="">-- Pilih Metode Pembayaran --</option>
                                <option value="Transfer Bank" {{ $metodeTerpilih == 'Transfer Bank' ? 'selected' : '' }}>
                                    <i class="bi bi-credit-card me-1"></i> Transfer Bank
                                </option>
                                <option value="E-Wallet" {{ $metodeTerpilih == 'E-Wallet' ? 'selected' : '' }}>
                                    <i class="bi bi-wallet2 me-1"></i> E-Wallet (GCash, OVO, Dana)
                                </option>
                                @if($isPickupAtStore)
                                    <option value="Tunai di Toko" {{ $metodeTerpilih == 'Tunai di Toko' ? 'selected' : '' }}>
                                        Tunai saat pengambilan di toko
                                    </option>
                                @endif
                            </select>
                        </div>

                        <!-- Upload Bukti -->
                        <div class="mb-3 {{ $buktiWajibAwal ? '' : 'd-none' }}" id="proofUploadSection">
                            <label class="form-label fw-semibold" id="proofUploadLabel">
                                Bukti Pembayaran <span class="text-danger">*</span>
                            </label>
                            <div class="card border-2 border-dashed rounded-3 p-4 text-center" id="dropZone">
                                <div class="mb-2">
                                    <i class="bi bi-cloud-upload" style="font-size: 2rem; color: #6c757d;"></i>
                                </div>
                                <p class="text-muted mb-1">Drag & drop file di sini atau klik untuk memilih</p>
                                <small class="text-muted">Format: JPG, PNG, WebP • Maksimal 3MB</small>
                                <input type="file" name="bukti_pembayaran" class="d-none" id="fileInput" accept="image/jpeg,image/png,image/webp" {{ $buktiWajibAwal ? 'required' : '' }}>
                            </div>
                            <div id="filePreview" class="mt-2"></div>
                            <small class="text-muted d-block mt-2">
                                <i class="bi bi-info-circle me-1"></i> Bukti transfer/e-wallet wajib diunggah agar admin dapat memverifikasi pembayaran.
                            </small>
                        </div>

                        @if($isPickupAtStore)
                            <div class="alert alert-info small" id="cashPaymentInfo">
                                <i class="bi bi-shop me-1"></i> Pembayaran tunai dicatat sekarang dan dilunasi saat barang diambil. Admin mengonfirmasi setelah pembayaran diterima.
                            </div>
                        @endif

                        <!-- Keterangan -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Keterangan (Opsional)</label>
                            <textarea name="keterangan" class="form-control" rows="3" placeholder="Contoh: Sudah transfer ke rekening BCA, nomor referensi: 12345678" maxlength="500">{{ old('keterangan', $pembayaran?->keterangan_pembayaran) }}</textarea>
                            <small class="text-muted">Maks 500 karakter</small>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-brand btn-lg py-3 fw-bold">
                                <i class="bi bi-send-fill me-2"></i> <span id="paymentSubmitLabel">Kirim Bukti Pembayaran</span>
                            </button>
                            <a href="{{ route('customer.transaksi.show', $transaksi->id_transaksi) }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-1"></i> Kembali ke Detail Transaksi
                            </a>
                        </div>
                    </form>
                </div>

                <!-- Info Box -->
                <div class="alert alert-info mt-4">
                    <i class="bi bi-lightbulb me-2"></i>
                    <strong>Tips:</strong> Pastikan bukti pembayaran jelas dan terlihat nomor referensi/tanggal transfer. Admin akan memverifikasi dalam waktu maksimal 2x24 jam.
                </div>
            </div>
        </div>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const dropZone = document.getElementById('dropZone');
    const fileInput = document.getElementById('fileInput');
    const filePreview = document.getElementById('filePreview');
    const metodePembayaran = document.querySelector('[name="metode_pembayaran"]');
    const proofUploadSection = document.getElementById('proofUploadSection');
    const paymentSubmitLabel = document.getElementById('paymentSubmitLabel');
    const cashPaymentInfo = document.getElementById('cashPaymentInfo');

    function updatePaymentForm() {
        const isOnline = ['Transfer Bank', 'E-Wallet'].includes(metodePembayaran.value);
        proofUploadSection.classList.toggle('d-none', !isOnline);
        fileInput.required = isOnline;
        paymentSubmitLabel.textContent = isOnline ? 'Kirim Bukti Pembayaran' : 'Catat Pembayaran Tunai';

        if (cashPaymentInfo) {
            cashPaymentInfo.classList.toggle('d-none', isOnline);
        }

        if (!isOnline && fileInput.value) {
            fileInput.value = '';
            filePreview.innerHTML = '';
        }
    }

    metodePembayaran.addEventListener('change', updatePaymentForm);
    updatePaymentForm();

    // Click to upload
    dropZone.addEventListener('click', () => fileInput.click());

    // Drag and drop
    dropZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropZone.classList.add('border-primary', 'bg-light');
    });

    dropZone.addEventListener('dragleave', () => {
        dropZone.classList.remove('border-primary', 'bg-light');
    });

    dropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropZone.classList.remove('border-primary', 'bg-light');
        fileInput.files = e.dataTransfer.files;
        previewFile();
    });

    fileInput.addEventListener('change', previewFile);

    function previewFile() {
        filePreview.innerHTML = '';
        if (fileInput.files.length > 0) {
            const file = fileInput.files[0];
            const reader = new FileReader();
            reader.onload = (e) => {
                filePreview.innerHTML = `
                    <div class="position-relative">
                        <img src="${e.target.result}" alt="Preview" class="img-fluid rounded-3" style="max-height: 200px; object-fit: cover;">
                        <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-2" onclick="clearFile()">
                            <i class="bi bi-x"></i> Hapus
                        </button>
                        <small class="d-block mt-2 text-muted">${file.name} (${(file.size / 1024 / 1024).toFixed(2)} MB)</small>
                    </div>
                `;
            };
            reader.readAsDataURL(file);
        }
    }
});

function clearFile() {
    const fileInput = document.getElementById('fileInput');
    const filePreview = document.getElementById('filePreview');
    fileInput.value = '';
    filePreview.innerHTML = '';
}
</script>
@endsection
