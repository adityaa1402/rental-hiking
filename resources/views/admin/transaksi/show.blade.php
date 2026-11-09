@extends('layouts.admin')

@section('title', 'Detail Transaksi')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h4 class="fw-bold mb-1">Rincian Transaksi: <span class="font-monospace text-primary">{{ $transaksi->id_transaksi }}</span></h4>
        <small class="text-muted">Diajukan pada {{ \Carbon\Carbon::parse($transaksi->created_at)->format('d/m/Y H:i') }} • 
            @php
                $statusClass = match($transaksi->status_transaksi) {
                    'Pending' => 'warning',
                    'Disetujui' => 'info',
                    'Berjalan' => 'primary',
                    'Selesai' => 'success',
                    'Ditolak' => 'danger',
                    default => 'secondary'
                };
            @endphp
            <span class="badge bg-{{ $statusClass }}">{{ $transaksi->status_transaksi }}</span>
        </small>
    </div>
    <a href="{{ route('admin.transaksi.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Kembali
    </a>
</div>

<div class="row g-4">
    <!-- Kolom Kiri: Rincian & Aksi -->
    <div class="col-lg-8">
        <!-- Panel Aksi -->
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
            <h6 class="fw-bold mb-3 border-bottom pb-2"><i class="bi bi-toggles text-warning me-2"></i>Tindakan & Kontrol Status</h6>
            @if($errors->any())
                <div class="alert alert-danger small">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if($transaksi->status_transaksi === 'Pending')
                <div class="alert alert-warning d-flex align-items-center mb-3">
                    <i class="bi bi-exclamation-circle me-3 fs-4"></i>
                    <div>
                        <strong>Transaksi Menunggu Persetujuan</strong><br>
                        <small>Periksa dokumen dan barang yang disewa tersedia sebelum menyetujui.</small>
                    </div>
                </div>

                <div class="d-flex gap-2 flex-wrap">
                    <form method="POST" action="{{ route('admin.transaksi.approve', $transaksi->id_transaksi) }}" 
                          onsubmit="return confirm('Setujui transaksi ini?');" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-success px-4">
                            <i class="bi bi-check2-circle me-1"></i> Setujui
                        </button>
                    </form>

                    <button type="button" class="btn btn-danger px-4" data-bs-toggle="modal" data-bs-target="#modalReject">
                        <i class="bi bi-x-circle me-1"></i> Tolak
                    </button>
                </div>

            @elseif($transaksi->status_transaksi === 'Disetujui')
                @if($transaksi->metode_pengambilan === 'Kirim via Kurir')
                    @if($transaksi->pembayaran?->status_pembayaran === 'Confirmed')
                        @php
                            $nextDeliveryStatus = match($transaksi->status_pengiriman) {
                                null => 'Disiapkan',
                                'Disiapkan' => 'Dalam Perjalanan',
                                'Dalam Perjalanan' => 'Terkirim',
                                default => null,
                            };
                        @endphp
                        @if($nextDeliveryStatus)
                            <form method="POST" action="{{ route('admin.transaksi.delivery', $transaksi->id_transaksi) }}">
                                @csrf
                                <div class="alert alert-info py-2 small mb-3">
                                    <i class="bi bi-truck me-1"></i>
                                    Pembayaran diterima. Perbarui status kurir ke tahap berikutnya: <strong>{{ $nextDeliveryStatus }}</strong>.
                                </div>
                                @if($nextDeliveryStatus === 'Disiapkan')
                                    <div class="row g-2 mb-3">
                                        <div class="col-md-4">
                                            <label class="form-label small fw-semibold">Nama Kurir</label>
                                            <input name="nama_kurir" class="form-control form-control-sm @error('nama_kurir') is-invalid @enderror"
                                                value="{{ old('nama_kurir', $transaksi->nama_kurir) }}" required maxlength="100">
                                            @error('nama_kurir')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label small fw-semibold">Telepon Kurir</label>
                                            <input name="telepon_kurir" class="form-control form-control-sm"
                                                value="{{ old('telepon_kurir', $transaksi->telepon_kurir) }}" maxlength="25">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label small fw-semibold">Nomor Resi</label>
                                            <input name="nomor_resi" class="form-control form-control-sm"
                                                value="{{ old('nomor_resi', $transaksi->nomor_resi) }}" maxlength="100">
                                        </div>
                                    </div>
                                @else
                                    <div class="row g-2 mb-3">
                                        <div class="col-md-4">
                                            <label class="form-label small fw-semibold">Nama Kurir</label>
                                            <input name="nama_kurir" class="form-control form-control-sm"
                                                value="{{ old('nama_kurir', $transaksi->nama_kurir) }}" maxlength="100">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label small fw-semibold">Telepon Kurir</label>
                                            <input name="telepon_kurir" class="form-control form-control-sm"
                                                value="{{ old('telepon_kurir', $transaksi->telepon_kurir) }}" maxlength="25">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label small fw-semibold">Nomor Resi</label>
                                            <input name="nomor_resi" class="form-control form-control-sm"
                                                value="{{ old('nomor_resi', $transaksi->nomor_resi) }}" maxlength="100">
                                        </div>
                                    </div>
                                @endif
                                <button type="submit" class="btn btn-primary px-4 py-2"
                                    onclick="return confirm('Perbarui status pengiriman menjadi {{ $nextDeliveryStatus }}?')">
                                    <i class="bi bi-truck me-1"></i> Tandai {{ $nextDeliveryStatus }}
                                </button>
                            </form>
                        @else
                            <div class="alert alert-success mb-0">
                                <i class="bi bi-check-circle me-1"></i> Pengiriman sudah diterima pelanggan.
                            </div>
                        @endif
                    @else
                        <div class="alert alert-warning mb-0">
                            <i class="bi bi-hourglass-split me-1"></i>
                            Siapkan pengiriman setelah pembayaran pelanggan dikonfirmasi.
                        </div>
                    @endif
                @else
                    @if($transaksi->pembayaran?->status_pembayaran === 'Confirmed')
                        <div class="alert alert-info py-2 small mb-3">
                            <i class="bi bi-info-circle me-1"></i> Pembayaran terkonfirmasi. Pastikan tanggal sewa sudah dimulai dan barang benar-benar diserahkan.
                        </div>
                        <form method="POST" action="{{ route('admin.transaksi.start', $transaksi->id_transaksi) }}"
                              onsubmit="return confirm('Konfirmasi barang telah diserahkan?');" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-primary px-4 py-2">
                                <i class="bi bi-box-arrow-up-right me-1"></i> Mulai Sewa (Berjalan)
                            </button>
                        </form>
                    @else
                        <div class="alert alert-warning mb-0">
                            <i class="bi bi-hourglass-split me-1"></i> Serah-terima hanya dapat dilakukan setelah pembayaran dikonfirmasi.
                        </div>
                    @endif
                @endif

            @elseif($transaksi->status_transaksi === 'Berjalan')
                <div class="alert alert-primary py-2 small mb-3">
                    <i class="bi bi-arrow-repeat me-1"></i> Barang sedang disewa. Jadwal kembali: <strong>{{ \Carbon\Carbon::parse($transaksi->tgl_kembali)->format('d/m/Y') }}</strong>
                </div>
                <a href="{{ route('admin.pengembalian.show', $transaksi->id_transaksi) }}" class="btn btn-success px-4 py-2">
                    <i class="bi bi-box-arrow-in-left me-1"></i> Proses Pengembalian Barang
                </a>

            @elseif($transaksi->status_transaksi === 'Selesai')
                <div class="alert alert-success py-2 small mb-0">
                    <i class="bi bi-check-all me-1"></i> Transaksi selesai. Barang telah kembali ke inventaris.
                </div>

            @else
                <div class="alert alert-secondary py-2 small mb-0">
                    Transaksi berstatus <strong>{{ $transaksi->status_transaksi }}</strong>. Tidak ada aksi tersedia.
                </div>
            @endif
        </div>

        @if($transaksi->metode_pengambilan === 'Kirim via Kurir' && $transaksi->status_pengiriman)
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
                <h6 class="fw-bold mb-3 border-bottom pb-2"><i class="bi bi-truck me-2"></i>Informasi Pengiriman</h6>
                <div class="row g-3 small">
                    <div class="col-md-6">
                        <span class="text-muted d-block">Status</span>
                        <strong>{{ $transaksi->status_pengiriman }}</strong>
                    </div>
                    @if($transaksi->nama_kurir)
                        <div class="col-md-6">
                            <span class="text-muted d-block">Kurir</span>
                            <strong>{{ $transaksi->nama_kurir }}</strong>
                        </div>
                    @endif
                    @if($transaksi->telepon_kurir)
                        <div class="col-md-6">
                            <span class="text-muted d-block">Telepon Kurir</span>
                            <strong>{{ $transaksi->telepon_kurir }}</strong>
                        </div>
                    @endif
                    @if($transaksi->nomor_resi)
                        <div class="col-md-6">
                            <span class="text-muted d-block">Nomor Resi</span>
                            <strong>{{ $transaksi->nomor_resi }}</strong>
                        </div>
                    @endif
                    @if($transaksi->dikirim_pada)
                        <div class="col-md-6">
                            <span class="text-muted d-block">Dikirim</span>
                            <strong>{{ $transaksi->dikirim_pada->format('d/m/Y H:i') }}</strong>
                        </div>
                    @endif
                    @if($transaksi->diterima_pada)
                        <div class="col-md-6">
                            <span class="text-muted d-block">Sampai di lokasi</span>
                            <strong>{{ $transaksi->diterima_pada->format('d/m/Y H:i') }}</strong>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <!-- Detail Barang -->
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
            <h6 class="fw-bold mb-3 border-bottom pb-2">Rincian Peralatan</h6>
            <div class="table-responsive">
                <table class="table table-bordered align-middle mb-0 small">
                    <thead class="table-light">
                        <tr>
                            <th>Barang</th>
                            <th class="text-center" style="width:80px">Qty</th>
                            <th class="text-end" style="width:120px">Harga/Hari</th>
                            <th class="text-end" style="width:120px">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transaksi->detailTransaksi as $detail)
                            <tr>
                                <td>
                                    <div class="fw-bold">{{ $detail->alatHiking->nama_alat ?? 'N/A' }}</div>
                                    <small class="text-muted">Merk: {{ $detail->alatHiking->merk ?? '' }}</small>
                                </td>
                                <td class="text-center">{{ $detail->jumlah_unit }}</td>
                                <td class="text-end">{{ formatRupiah($detail->harga_per_hari_snapshot) }}</td>
                                <td class="text-end font-monospace fw-bold">{{ formatRupiah($detail->subtotal) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-3 text-muted">Tidak ada barang dalam transaksi ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Ringkasan Biaya -->
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
            <h6 class="fw-bold mb-3 border-bottom pb-2">Ringkasan Biaya</h6>
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="d-flex justify-content-between mb-2 small">
                        <span>Periode Sewa:</span>
                        <span class="fw-bold">{{ \Carbon\Carbon::parse($transaksi->tgl_sewa)->format('d/m/Y') }} s/d {{ \Carbon\Carbon::parse($transaksi->tgl_kembali)->format('d/m/Y') }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2 small">
                        <span>Total Hari:</span>
                        <span class="fw-bold">{{ $transaksi->total_hari }} Hari</span>
                    </div>
                </div>
                <div class="col-md-6 border-start ps-4">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal:</span>
                        <span class="font-monospace fw-bold">{{ formatRupiah($transaksi->total_harga) }}</span>
                    </div>
                    @if($transaksi->denda > 0)
                        <div class="d-flex justify-content-between mb-2 text-danger">
                            <span>Denda keterlambatan:</span>
                            <span class="font-monospace fw-bold">{{ formatRupiah($transaksi->denda) }}</span>
                        </div>
                    @endif
                    <div class="d-flex justify-content-between border-top pt-2">
                        <span class="fw-bold">Total Transaksi:</span>
                        <span class="font-monospace fw-bold text-success" style="font-size:1.2rem;">{{ formatRupiah($transaksi->total_harga + $transaksi->denda) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Kolom Kanan: Info Pelanggan -->
    <div class="col-lg-4">
        <!-- Card Pelanggan -->
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
            <h6 class="fw-bold mb-3 border-bottom pb-2"><i class="bi bi-person me-2"></i>Data Pelanggan</h6>
            <div class="row g-2">
                <div class="col-12">
                    <small class="text-muted d-block">Nama Lengkap</small>
                    <div class="fw-bold">{{ $transaksi->user->nama_lengkap ?? 'N/A' }}</div>
                </div>
                <div class="col-12">
                    <small class="text-muted d-block">Email</small>
                    <div class="text-break">{{ $transaksi->user->email ?? '-' }}</div>
                </div>
                <div class="col-12">
                    <small class="text-muted d-block">No. Telepon</small>
                    <div>{{ $transaksi->user->no_hp ?? '-' }}</div>
                </div>
                <div class="col-12">
                    <small class="text-muted d-block">Alamat</small>
                    <div class="small text-secondary">{{ $transaksi->user->alamat ?? '-' }}</div>
                </div>
            </div>
        </div>

        <!-- Pembayaran -->
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
            <h6 class="fw-bold mb-3 border-bottom pb-2"><i class="bi bi-wallet2 me-2"></i>Pembayaran Pelanggan</h6>
            @if($transaksi->pembayaran)
                @php
                    $pembayaran = $transaksi->pembayaran;
                    $paymentStatusClass = match($pembayaran->status_pembayaran) {
                        'Confirmed' => 'success',
                        'Rejected' => 'danger',
                        'Refunded' => 'info',
                        default => 'warning text-dark',
                    };
                @endphp
                <div class="mb-3">
                    <small class="text-muted d-block">Status Pembayaran</small>
                    <span class="badge bg-{{ $paymentStatusClass }}">{{ $pembayaran->status_pembayaran }}</span>
                </div>
                <div class="mb-3">
                    <small class="text-muted d-block">Metode</small>
                    <div class="fw-semibold">{{ $pembayaran->metode_pembayaran }}</div>
                </div>
                <div class="mb-3">
                    <small class="text-muted d-block">Jumlah</small>
                    <div class="fw-semibold">{{ formatRupiah($pembayaran->jumlah) }}</div>
                </div>

                @if($pembayaran->bukti_pembayaran)
                    @php
                        $buktiAda = \Illuminate\Support\Facades\Storage::disk('public')->exists($pembayaran->bukti_pembayaran);
                        $buktiUrl = route('admin.payment.proof', $pembayaran->id_pembayaran);
                    @endphp
                    @if($buktiAda)
                        <div class="border-top pt-3">
                            <small class="text-muted d-block mb-2">Bukti Pembayaran</small>
                            <a href="{{ $buktiUrl }}" target="_blank" rel="noopener noreferrer" class="d-block">
                                <img src="{{ $buktiUrl }}" alt="Bukti pembayaran pelanggan"
                                    class="img-fluid rounded border w-100" style="max-height: 320px; object-fit: contain;">
                            </a>
                            <a href="{{ $buktiUrl }}" target="_blank" rel="noopener noreferrer" class="btn btn-outline-primary btn-sm w-100 mt-2">
                                <i class="bi bi-box-arrow-up-right me-1"></i> Lihat Bukti Ukuran Penuh
                            </a>
                            @if($pembayaran->keterangan_pembayaran)
                                <div class="small text-muted mt-3">
                                    <strong>Keterangan pelanggan:</strong>
                                    <div class="text-body">{{ $pembayaran->keterangan_pembayaran }}</div>
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="alert alert-warning small mb-0">
                            File bukti pembayaran tercatat, tetapi tidak ditemukan di penyimpanan.
                        </div>
                    @endif
                @elseif($pembayaran->metode_pembayaran === 'Tunai di Toko')
                    <div class="alert alert-info small mb-0">
                        Pembayaran tunai di toko; belum ada bukti transfer yang diunggah.
                    </div>
                @else
                    <div class="alert alert-warning small mb-0">
                        Pelanggan belum mengunggah bukti pembayaran.
                    </div>
                @endif

                @if($pembayaran->status_pembayaran === 'Pending')
                    <a href="{{ route('admin.payment.show', $pembayaran->id_pembayaran) }}" class="btn btn-success btn-sm w-100 mt-3">
                        <i class="bi bi-check2-circle me-1"></i> Buka Verifikasi Pembayaran
                    </a>
                @endif
            @else
                <div class="alert alert-secondary small mb-0">
                    Belum ada pembayaran yang dicatat untuk transaksi ini.
                </div>
            @endif
        </div>

        <!-- Timeline Status -->
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
            <h6 class="fw-bold mb-3 border-bottom pb-2"><i class="bi bi-clock-history me-2"></i>Timeline</h6>
            <div class="small">
                <div class="mb-3 pb-3 border-bottom">
                    <div class="badge bg-success mb-2">Dibuat</div>
                    <div class="text-muted">{{ \Carbon\Carbon::parse($transaksi->created_at)->format('d/m/Y H:i') }}</div>
                </div>
                <div class="mb-3 pb-3 border-bottom">
                    <div class="badge bg-info mb-2">Status Saat Ini</div>
                    <div class="fw-bold">{{ $transaksi->status_transaksi }}</div>
                    <div class="small text-muted">{{ \Carbon\Carbon::parse($transaksi->updated_at)->format('d/m/Y H:i') }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tolak -->
<div class="modal fade" id="modalReject" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0">
            <div class="modal-header border-bottom">
                <h6 class="modal-title fw-bold">Tolak Transaksi</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('admin.transaksi.reject', $transaksi->id_transaksi) }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Alasan Penolakan</label>
                        <textarea name="alasan" class="form-control form-control-sm" rows="4" required 
                                  placeholder="Jelaskan alasan penolakan..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Tolak Transaksi</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
