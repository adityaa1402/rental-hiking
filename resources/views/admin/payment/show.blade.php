@extends('layouts.app')

@section('content')
<!-- Header Banner -->
<div class="py-4" style="background-color: var(--forest-green); color: #ffffff;">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-1">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-white-50 text-decoration-none">Admin</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.payment.index') }}" class="text-white-50 text-decoration-none">Verifikasi Pembayaran</a></li>
                <li class="breadcrumb-item active text-white" aria-current="page">Detail Pembayaran</li>
            </ol>
        </nav>
        <h2 class="fw-bold mb-0">Verifikasi Pembayaran</h2>
        <p class="text-white-50 small mb-0">Review dan verifikasi bukti pembayaran pelanggan</p>
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

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row g-4">
            <!-- Main Content -->
            <div class="col-lg-8">
                <!-- Transaksi Info -->
                <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                    <h5 class="fw-bold mb-3"><i class="bi bi-person-circle text-primary me-2"></i>Informasi Pelanggan</h5>
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <small class="text-muted d-block">Nama</small>
                            <div class="fw-semibold">{{ $pembayaran->transaksi->user->nama_lengkap }}</div>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block">Email</small>
                            <div class="fw-semibold">{{ $pembayaran->transaksi->user->email }}</div>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block">No. HP</small>
                            <div class="fw-semibold">{{ $pembayaran->transaksi->user->no_hp }}</div>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block">Alamat</small>
                            <div class="small">{{ $pembayaran->transaksi->user->alamat }}</div>
                        </div>
                    </div>
                </div>

                <!-- Transaksi Details -->
                <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                    <h5 class="fw-bold mb-3"><i class="bi bi-receipt text-success me-2"></i>Detail Transaksi</h5>
                    
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <small class="text-muted d-block">Kode Transaksi</small>
                            <div class="fw-bold">{{ $pembayaran->transaksi->kode_transaksi }}</div>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block">Tanggal Booking</small>
                            <div class="small">{{ $pembayaran->transaksi->created_at->format('d M Y H:i') }}</div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Item</th>
                                    <th class="text-end">Qty</th>
                                    <th class="text-end">Harga/Hari</th>
                                    <th class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pembayaran->transaksi->detailTransaksi as $item)
                                    <tr>
                                        <td>{{ $item->alatHiking->nama_alat }}</td>
                                        <td class="text-end">{{ $item->jumlah_unit }}</td>
                                        <td class="text-end">{{ formatRupiah($item->harga_per_hari_snapshot) }}</td>
                                        <td class="text-end fw-semibold">{{ formatRupiah($item->subtotal) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="border-top mt-3 pt-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal:</span>
                            <strong>{{ formatRupiah($pembayaran->transaksi->subtotal) }}</strong>
                        </div>
                        @if($pembayaran->transaksi->biaya_pengiriman > 0)
                            <div class="d-flex justify-content-between mb-2">
                                <span>Biaya Pengiriman:</span>
                                <strong>{{ formatRupiah($pembayaran->transaksi->biaya_pengiriman) }}</strong>
                            </div>
                        @endif
                        <div class="d-flex justify-content-between p-2 rounded-2" style="background-color: #f8f9fa;">
                            <span class="fw-bold">Total:</span>
                            <strong class="text-success" style="font-size: 1.1rem;">{{ formatRupiah($pembayaran->jumlah) }}</strong>
                        </div>
                    </div>
                </div>

                <!-- Payment Proof -->
                @if($pembayaran->bukti_pembayaran)
                    <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                        <h5 class="fw-bold mb-3"><i class="bi bi-image text-primary me-2"></i>Bukti Pembayaran</h5>
                        
                        <div class="text-center">
                            @if(\Illuminate\Support\Facades\Storage::disk('public')->exists($pembayaran->bukti_pembayaran))
                                <img src="{{ route('admin.payment.proof', $pembayaran->id_pembayaran) }}" alt="Bukti Pembayaran" class="img-fluid rounded-3" style="max-height: 400px; object-fit: contain;">
                                <small class="d-block text-muted mt-2">{{ basename($pembayaran->bukti_pembayaran) }}</small>
                                <a href="{{ route('admin.payment.proof', $pembayaran->id_pembayaran) }}" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-outline-primary mt-2">
                                    <i class="bi bi-box-arrow-up-right me-1"></i> Buka Bukti
                                </a>
                            @else
                                <div class="alert alert-warning mb-0">File bukti pembayaran tidak ditemukan di penyimpanan.</div>
                            @endif
                        </div>

                        @if($pembayaran->keterangan_pembayaran)
                            <div class="mt-3 p-3 rounded-2" style="background-color: #f8f9fa;">
                                <small class="text-muted d-block">Keterangan dari Pelanggan:</small>
                                <p class="mb-0 small">{{ $pembayaran->keterangan_pembayaran }}</p>
                            </div>
                        @endif
                    </div>
                @else
                    <div class="alert alert-warning">
                        @if($pembayaran->metode_pembayaran === 'Tunai di Toko')
                            <i class="bi bi-shop me-2"></i> Pembayaran tunai menunggu pengambilan barang. Konfirmasi setelah uang diterima.
                        @else
                            <i class="bi bi-exclamation-triangle me-2"></i> Pelanggan belum mengupload bukti pembayaran online.
                        @endif
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Payment Status Card -->
                <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                    <h5 class="fw-bold mb-3"><i class="bi bi-info-circle text-primary me-2"></i>Status Pembayaran</h5>
                    
                    <div class="mb-3">
                        <small class="text-muted d-block mb-2">Status Saat Ini</small>
                        <div>
                            @if($pembayaran->status_pembayaran === 'Pending')
                                <span class="badge bg-warning text-dark px-3 py-2">
                                    <i class="bi bi-clock-history me-1"></i> Menunggu Verifikasi
                                </span>
                            @elseif($pembayaran->status_pembayaran === 'Confirmed')
                                <span class="badge bg-success px-3 py-2">
                                    <i class="bi bi-check-circle me-1"></i> Dikonfirmasi
                                </span>
                            @elseif($pembayaran->status_pembayaran === 'Rejected')
                                <span class="badge bg-danger px-3 py-2">
                                    <i class="bi bi-x-circle me-1"></i> Ditolak
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3 border-bottom pb-3">
                        <small class="text-muted d-block">Metode Pembayaran</small>
                        <div class="small fw-semibold">{{ $pembayaran->metode_pembayaran }}</div>
                    </div>

                    <div class="mb-3">
                        <small class="text-muted d-block">Diupload Pada</small>
                        <div class="small">{{ $pembayaran->created_at->format('d M Y H:i') }}</div>
                    </div>

                    @if($pembayaran->tanggal_dikonfirmasi)
                        <div class="mb-3 p-3 rounded-2" style="background-color: #f8f9fa;">
                            <small class="text-muted d-block">Diverifikasi Oleh</small>
                            <div class="small fw-semibold">{{ $pembayaran->admin->nama_lengkap ?? 'Admin' }}</div>
                            <small class="text-muted">{{ $pembayaran->tanggal_dikonfirmasi->format('d M Y H:i') }}</small>
                        </div>
                    @endif

                    @if($pembayaran->catatan_admin && $pembayaran->status_pembayaran === 'Rejected')
                        <div class="alert alert-danger small mb-0">
                            <strong>Alasan Penolakan:</strong>
                            <p class="mb-0 mt-2">{{ $pembayaran->catatan_admin }}</p>
                        </div>
                    @endif
                </div>

                <!-- Action Buttons -->
                @if($pembayaran->status_pembayaran === 'Pending')
                    <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                        <h5 class="fw-bold mb-3"><i class="bi bi-check2-all text-success me-2"></i>Verifikasi</h5>
                        
                        <!-- Approve -->
                        <form action="{{ route('admin.payment.approve', $pembayaran->id_pembayaran) }}" method="POST" class="mb-2">
                            @csrf
                            <button type="submit" class="btn btn-success w-100 fw-bold py-2" onclick="return confirm('Konfirmasi pembayaran ini?')">
                                <i class="bi bi-check-circle me-1"></i> Setujui Pembayaran
                            </button>
                        </form>

                        <!-- Reject Modal -->
                        <button type="button" class="btn btn-outline-danger w-100 fw-bold py-2" data-bs-toggle="modal" data-bs-target="#rejectModal">
                            <i class="bi bi-x-circle me-1"></i> Tolak Pembayaran
                        </button>
                    </div>
                @endif

                <!-- Navigation -->
                <div class="card border-0 shadow-sm rounded-4 p-4">
                    <a href="{{ route('admin.payment.index') }}" class="btn btn-outline-primary w-100 py-2">
                        <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
                    </a>
                    <a href="{{ route('admin.transaksi.show', $pembayaran->transaksi->id_transaksi) }}" class="btn btn-outline-secondary w-100 py-2 mt-2">
                        <i class="bi bi-receipt me-1"></i> Lihat Transaksi
                    </a>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title fw-bold"><i class="bi bi-exclamation-triangle text-danger me-2"></i>Tolak Pembayaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            
            <form action="{{ route('admin.payment.reject', $pembayaran->id_pembayaran) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <label class="form-label fw-semibold">Alasan Penolakan <span class="text-danger">*</span></label>
                    <textarea name="alasan_penolakan" class="form-control" rows="4" placeholder="Jelaskan mengapa pembayaran ditolak..." required maxlength="500"></textarea>
                    <small class="text-muted">Maks 500 karakter</small>
                    
                    <div class="alert alert-info mt-3 small">
                        <i class="bi bi-info-circle me-1"></i> Pelanggan akan melihat alasan ini dan dapat mengupload ulang bukti pembayaran
                    </div>
                </div>

                <div class="modal-footer border-top-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger fw-bold">
                        <i class="bi bi-x-circle me-1"></i> Tolak Pembayaran
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
