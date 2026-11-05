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
                <div class="alert alert-info py-2 small mb-3">
                    <i class="bi bi-info-circle me-1"></i> Transaksi disetujui. Siap diserahkan kepada pelanggan.
                </div>
                <form method="POST" action="{{ route('admin.transaksi.start', $transaksi->id_transaksi) }}" 
                      onsubmit="return confirm('Konfirmasi barang telah diserahkan?');" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-primary px-4 py-2">
                        <i class="bi bi-box-arrow-up-right me-1"></i> Mulai Sewa (Berjalan)
                    </button>
                </form>

            @elseif($transaksi->status_transaksi === 'Berjalan')
                <div class="alert alert-primary py-2 small mb-3">
                    <i class="bi bi-arrow-repeat me-1"></i> Barang sedang disewa. Jadwal kembali: <strong>{{ \Carbon\Carbon::parse($transaksi->tgl_kembali)->format('d/m/Y') }}</strong>
                </div>
                <form method="POST" action="{{ route('admin.transaksi.complete', $transaksi->id_transaksi) }}" 
                      onsubmit="return confirm('Konfirmasi pengembalian barang?');" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-success px-4 py-2">
                        <i class="bi bi-box-arrow-in-left me-1"></i> Selesaikan (Barang Dikembalikan)
                    </button>
                </form>

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
                                    <div class="fw-bold">{{ $detail->alatHiking->nama_barang ?? 'N/A' }}</div>
                                    <small class="text-muted">Merk: {{ $detail->alatHiking->merk ?? '' }}</small>
                                </td>
                                <td class="text-center">{{ $detail->jumlah_unit }}</td>
                                <td class="text-end">{{ formatRupiah($detail->harga_sewa_perhari) }}</td>
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
                    <div class="d-flex justify-content-between border-top pt-2">
                        <span class="fw-bold">Total Transaksi:</span>
                        <span class="font-monospace fw-bold text-success" style="font-size:1.2rem;">{{ formatRupiah($transaksi->total_harga) }}</span>
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
