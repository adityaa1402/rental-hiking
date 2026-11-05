@extends('layouts.admin')

@section('title', 'Proses Pengembalian')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h4 class="fw-bold mb-1">Proses Pengembalian: {{ $transaksi->id_transaksi }}</h4>
        <small class="text-muted">Verifikasi kondisi barang dan hitung denda keterlambatan.</small>
    </div>
    <a href="{{ route('admin.pengembalian.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Kembali
    </a>
</div>

<!-- Info Transaksi -->
<div class="row mb-4 g-2">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white small">
            <div class="text-muted mb-1">Periode Sewa</div>
            <div class="fw-bold">{{ \Carbon\Carbon::parse($transaksi->tgl_sewa)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($transaksi->tgl_kembali)->format('d/m/Y') }}</div>
            <div class="small text-muted">{{ $transaksi->total_hari }} Hari</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white small">
            <div class="text-muted mb-1">Pelanggan</div>
            <div class="fw-bold">{{ $transaksi->user->nama_lengkap }}</div>
            <div class="small text-muted">{{ $transaksi->user->email }}</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white small">
            <div class="text-muted mb-1">Total Biaya</div>
            <div class="fw-bold text-success">{{ formatRupiah($transaksi->total_harga) }}</div>
        </div>
    </div>
</div>

<!-- Form Pengembalian -->
<form method="POST" action="{{ route('admin.pengembalian.proses', $transaksi->id_transaksi) }}" class="needs-validation">
    @csrf

    <div class="row g-4">
        <!-- Kolom Kiri: Verifikasi -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
                <h6 class="fw-bold mb-3 border-bottom pb-2">Verifikasi Pengembalian Barang</h6>

                <div class="mb-3">
                    <label class="form-label fw-bold">Tanggal Pengembalian Aktual</label>
                    <input type="date" name="tanggal_pengembalian_aktual" class="form-control form-control-sm @error('tanggal_pengembalian_aktual') is-invalid @enderror" 
                           value="{{ old('tanggal_pengembalian_aktual', now()->format('Y-m-d')) }}" required>
                    @error('tanggal_pengembalian_aktual')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Kondisi Pengembalian</label>
                    <input type="text" name="kondisi_pengembalian" class="form-control form-control-sm @error('kondisi_pengembalian') is-invalid @enderror" 
                           placeholder="Contoh: Baik, Ada Lecet, Rusak Berat" value="{{ old('kondisi_pengembalian') }}" required>
                    @error('kondisi_pengembalian')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Jumlah Baik</label>
                        <input type="number" name="jumlah_baik" class="form-control form-control-sm @error('jumlah_baik') is-invalid @enderror" 
                               value="{{ old('jumlah_baik', 0) }}" min="0" required>
                        @error('jumlah_baik')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Jumlah Rusak</label>
                        <input type="number" name="jumlah_rusak" class="form-control form-control-sm @error('jumlah_rusak') is-invalid @enderror" 
                               value="{{ old('jumlah_rusak', 0) }}" min="0" required>
                        @error('jumlah_rusak')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Jumlah Hilang</label>
                        <input type="number" name="jumlah_hilang" class="form-control form-control-sm @error('jumlah_hilang') is-invalid @enderror" 
                               value="{{ old('jumlah_hilang', 0) }}" min="0" required>
                        @error('jumlah_hilang')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Catatan Tambahan</label>
                    <textarea name="catatan" class="form-control form-control-sm" rows="3" placeholder="Catatan kondisi barang atau kejadian khusus...">{{ old('catatan') }}</textarea>
                </div>
            </div>

            <!-- Daftar Barang -->
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
                <h6 class="fw-bold mb-3 border-bottom pb-2">Daftar Barang yang Dikembalikan</h6>
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead class="table-light small">
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
                                    <td>{{ $detail->alatHiking->nama_barang ?? 'N/A' }}</td>
                                    <td class="text-center">{{ $detail->jumlah_unit }}</td>
                                    <td class="text-end">{{ formatRupiah($detail->harga_sewa_perhari) }}</td>
                                    <td class="text-end fw-bold">{{ formatRupiah($detail->subtotal) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-3 text-muted">Tidak ada barang.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Ringkasan Denda -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                <h6 class="fw-bold mb-3 border-bottom pb-2">Ringkasan Biaya</h6>
                
                <div class="row g-2 small mb-3">
                    <div class="col-12">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Biaya Sewa:</span>
                            <span class="fw-bold">{{ formatRupiah($transaksi->total_harga) }}</span>
                        </div>
                    </div>
                    <div class="col-12 border-top pt-2">
                        <div class="text-muted small mb-2">Denda Keterlambatan</div>
                        @php
                            $hariTerlambat = max(0, $transaksi->tgl_kembali->diffInDays(now()));
                            $dendaPerHari = 50000; // from setting
                            $totalDenda = $hariTerlambat * $dendaPerHari;
                        @endphp
                        <div class="d-flex justify-content-between mb-1">
                            <span class="small">Hari terlambat:</span>
                            <span class="fw-bold">{{ $hariTerlambat }} hari</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="small">Denda (@Rp50.000/hari):</span>
                            <span class="fw-bold text-danger">{{ formatRupiah($totalDenda) }}</span>
                        </div>
                    </div>
                    <div class="col-12 border-top pt-2">
                        <div class="d-flex justify-content-between">
                            <span class="fw-bold">Total:</span>
                            <span class="fw-bold text-success" style="font-size:1.1rem;">{{ formatRupiah($transaksi->total_harga + $totalDenda) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-success w-100 mt-4">
                <i class="bi bi-check-circle me-1"></i> Selesaikan Pengembalian
            </button>
        </div>
    </div>
</form>

@endsection
