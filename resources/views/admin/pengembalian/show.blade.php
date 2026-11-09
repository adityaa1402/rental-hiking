@extends('layouts.admin')

@section('title', 'Proses Pengembalian')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h4 class="fw-bold mb-1">Proses Pengembalian: {{ $transaksi->id_transaksi }}</h4>
        <small class="text-muted">Verifikasi kondisi barang dan hitung denda keterlambatan.</small>
    </div>
    <a href="{{ route('admin.transaksi.show', $transaksi->id_transaksi) }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Kembali ke Transaksi
    </a>
</div>

@if(session('info'))
    <div class="alert alert-info">{{ session('info') }}</div>
@endif
@if($errors->any())
    <div class="alert alert-danger">
        <strong>Pengembalian belum dapat diproses:</strong>
        <ul class="mb-0 mt-2">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

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

@if($transaksi->status_transaksi === 'Selesai')
    @if($transaksi->pengembalian)
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
            <h5 class="fw-bold mb-3"><i class="bi bi-check-circle text-success me-2"></i>Pengembalian Sudah Diproses</h5>
            <div class="row g-3">
                <div class="col-md-6">
                    <small class="text-muted d-block">Tanggal Pengembalian Aktual</small>
                    <strong>{{ \Carbon\Carbon::parse($transaksi->pengembalian->tanggal_pengembalian_aktual)->format('d/m/Y') }}</strong>
                </div>
                <div class="col-md-6">
                    <small class="text-muted d-block">Kondisi Barang</small>
                    <strong>{{ $transaksi->pengembalian->kondisi_pengembalian }}</strong>
                </div>
                <div class="col-md-4">
                    <small class="text-muted d-block">Barang Baik</small>
                    <strong>{{ $transaksi->pengembalian->jumlah_baik }} unit</strong>
                </div>
                <div class="col-md-4">
                    <small class="text-muted d-block">Barang Rusak</small>
                    <strong>{{ $transaksi->pengembalian->jumlah_rusak }} unit</strong>
                </div>
                <div class="col-md-4">
                    <small class="text-muted d-block">Barang Hilang</small>
                    <strong>{{ $transaksi->pengembalian->jumlah_hilang }} unit</strong>
                </div>
                @if($transaksi->pengembalian->detailPengembalian->isNotEmpty())
                    <div class="col-12">
                        <small class="text-muted d-block mb-2">Rincian per Barang</small>
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered mb-0">
                                <thead>
                                    <tr>
                                        <th>Barang</th>
                                        <th>Baik</th>
                                        <th>Rusak</th>
                                        <th>Hilang</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($transaksi->pengembalian->detailPengembalian as $detail)
                                        <tr>
                                            <td>{{ $detail->alatHiking->nama_alat }}</td>
                                            <td>{{ $detail->jumlah_baik }}</td>
                                            <td>{{ $detail->jumlah_rusak }}</td>
                                            <td>{{ $detail->jumlah_hilang }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
                <div class="col-md-6">
                    <small class="text-muted d-block">Denda Keterlambatan</small>
                    <strong class="text-danger">{{ formatRupiah($transaksi->pengembalian->denda) }}</strong>
                </div>
                @if($transaksi->pengembalian->catatan)
                    <div class="col-12">
                        <small class="text-muted d-block">Catatan</small>
                        <div>{{ $transaksi->pengembalian->catatan }}</div>
                    </div>
                @endif
            </div>
        </div>
    @else
        <div class="alert alert-warning">Transaksi sudah selesai, tetapi belum memiliki catatan pemeriksaan pengembalian.</div>
    @endif
@else
<!-- Form Pengembalian -->
@php
    $tanggalMulaiSewa = \Carbon\Carbon::parse($transaksi->tgl_sewa)->startOfDay();
    $tanggalHariIni = now()->startOfDay();
    $bisaDiprosesHariIni = $tanggalMulaiSewa->lte($tanggalHariIni);
@endphp
@if(!$bisaDiprosesHariIni)
    <div class="alert alert-warning">
        <strong>Pengembalian belum dapat diproses.</strong>
        Masa sewa baru dimulai pada {{ $tanggalMulaiSewa->format('d/m/Y') }}.
        Tanggal pengembalian aktual adalah tanggal barang benar-benar diterima kembali, jadi tidak dapat diisi dengan tanggal masa depan.
    </div>
@else
    <div class="alert alert-info">
        <strong>Tanggal pengembalian aktual</strong> adalah tanggal barang secara fisik diterima kembali oleh petugas.
        Tanggal rencana kembali pada transaksi adalah {{ \Carbon\Carbon::parse($transaksi->tgl_kembali)->format('d/m/Y') }}.
        Jika barang dikembalikan pada tanggal rencana tersebut, pilih tanggal yang sama.
    </div>
@endif
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
                           value="{{ old('tanggal_pengembalian_aktual', now()->format('Y-m-d')) }}"
                           min="{{ $tanggalMulaiSewa->format('Y-m-d') }}"
                           max="{{ $tanggalHariIni->format('Y-m-d') }}"
                           data-tanggal-jadwal="{{ \Carbon\Carbon::parse($transaksi->tgl_kembali)->format('Y-m-d') }}"
                           {{ !$bisaDiprosesHariIni ? 'disabled' : '' }}
                           required>
                    @error('tanggal_pengembalian_aktual')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Kondisi Pengembalian</label>
                    <input type="text" name="kondisi_pengembalian" class="form-control form-control-sm @error('kondisi_pengembalian') is-invalid @enderror" 
                           placeholder="Contoh: Lengkap, ada lecet pada satu barang" value="{{ old('kondisi_pengembalian') }}" required>
                    @error('kondisi_pengembalian')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="alert alert-info small">
                    Periksa setiap jenis barang. Jumlah baik, rusak, dan hilang per barang harus sama dengan jumlah yang disewa.
                    Barang rusak akan dikeluarkan sementara dari stok siap sewa; barang hilang akan dikeluarkan dari stok total.
                </div>
                @error('barang')
                    <div class="alert alert-danger small">{{ $message }}</div>
                @enderror
                @foreach($barangDisewa as $idAlat => $data)
                    @php
                        $jumlahDisewaAlat = $data['jumlah'];
                        $jumlahBaikAwal = old("barang.{$idAlat}.jumlah_baik", $jumlahDisewaAlat);
                        $jumlahRusakAwal = old("barang.{$idAlat}.jumlah_rusak", 0);
                        $jumlahHilangAwal = old("barang.{$idAlat}.jumlah_hilang", 0);
                    @endphp
                    <div class="border rounded-3 p-3 mb-3">
                        <input type="hidden" name="barang[{{ $idAlat }}][id_alat]" value="{{ $idAlat }}">
                        <div class="fw-bold mb-1">{{ $data['alat']->nama_alat }}</div>
                        <div class="small text-muted mb-3">Disewa: {{ $jumlahDisewaAlat }} unit</div>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Baik</label>
                                <input type="number" name="barang[{{ $idAlat }}][jumlah_baik]"
                                    class="form-control form-control-sm" value="{{ $jumlahBaikAwal }}"
                                    min="0" max="{{ $jumlahDisewaAlat }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Rusak</label>
                                <input type="number" name="barang[{{ $idAlat }}][jumlah_rusak]"
                                    class="form-control form-control-sm" value="{{ $jumlahRusakAwal }}"
                                    min="0" max="{{ $jumlahDisewaAlat }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Hilang</label>
                                <input type="number" name="barang[{{ $idAlat }}][jumlah_hilang]"
                                    class="form-control form-control-sm" value="{{ $jumlahHilangAwal }}"
                                    min="0" max="{{ $jumlahDisewaAlat }}" required>
                            </div>
                        </div>
                        @foreach(['jumlah_baik', 'jumlah_rusak', 'jumlah_hilang'] as $field)
                            @if($errors->has("barang.{$idAlat}.{$field}"))
                                <div class="text-danger small mt-2">{{ $errors->first("barang.{$idAlat}.{$field}") }}</div>
                            @endif
                        @endforeach
                    </div>
                @endforeach

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
                                    <td>{{ $detail->alatHiking->nama_alat ?? 'N/A' }}</td>
                                    <td class="text-center">{{ $detail->jumlah_unit }}</td>
                                    <td class="text-end">{{ formatRupiah($detail->harga_per_hari_snapshot) }}</td>
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
                            $tanggalJadwal = \Carbon\Carbon::parse($transaksi->tgl_kembali)->startOfDay();
                            $tanggalInput = old('tanggal_pengembalian_aktual');
                            $tanggalAktual = $tanggalInput && strtotime($tanggalInput) !== false
                                ? \Carbon\Carbon::parse($tanggalInput)->startOfDay()
                                : now()->startOfDay();
                            $hariTerlambat = max(0, (int) $tanggalJadwal->diffInDays($tanggalAktual, false));
                            $dendaPerHari = (float) $dendaHarian;
                            $totalDenda = $hariTerlambat * $dendaPerHari;
                        @endphp
                        <div class="d-flex justify-content-between mb-1">
                            <span class="small">Hari terlambat:</span>
                            <span class="fw-bold" id="hariTerlambat">{{ $hariTerlambat }} hari</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="small">Denda ({{ formatRupiah($dendaPerHari) }}/hari):</span>
                            <span class="fw-bold text-danger" id="totalDenda">{{ formatRupiah($totalDenda) }}</span>
                        </div>
                    </div>
                    <div class="col-12 border-top pt-2">
                        <div class="d-flex justify-content-between">
                            <span class="fw-bold">Total:</span>
                            <span class="fw-bold text-success" id="totalBiaya" data-biaya-sewa="{{ $transaksi->total_harga }}" style="font-size:1.1rem;">{{ formatRupiah($transaksi->total_harga + $totalDenda) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-success w-100 mt-4" {{ !$bisaDiprosesHariIni ? 'disabled' : '' }} onclick="return confirm('Pastikan jumlah dan kondisi barang sudah diperiksa. Selesaikan pengembalian?')">
                <i class="bi bi-check-circle me-1"></i> Selesaikan Pengembalian
            </button>
        </div>
    </div>
</form>
@endif

@endsection

@if($transaksi->status_transaksi === 'Berjalan')
    @push('scripts')
        <script>
            const tanggalPengembalian = document.querySelector('[name="tanggal_pengembalian_aktual"]');
            const hariTerlambat = document.getElementById('hariTerlambat');
            const totalDenda = document.getElementById('totalDenda');
            const totalBiaya = document.getElementById('totalBiaya');
            const dendaPerHari = {{ (float) $dendaHarian }};
            const formatRupiah = amount => 'Rp ' + new Intl.NumberFormat('id-ID').format(amount);

            tanggalPengembalian?.addEventListener('change', () => {
                const actual = new Date(tanggalPengembalian.value + 'T00:00:00Z');
                const due = new Date(tanggalPengembalian.dataset.tanggalJadwal + 'T00:00:00Z');
                const lateDays = Math.max(0, Math.floor((actual - due) / 86400000));
                const fine = lateDays * dendaPerHari;

                hariTerlambat.textContent = lateDays + ' hari';
                totalDenda.textContent = formatRupiah(fine);
                totalBiaya.textContent = formatRupiah(Number(totalBiaya.dataset.biayaSewa) + fine);
            });
        </script>
    @endpush
@endif
