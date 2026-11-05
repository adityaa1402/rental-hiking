@extends('layouts.app')

@section('title', 'Detail Transaksi ' . $transaksi->kode_transaksi)

@section('content')

<div class="py-4" style="background:linear-gradient(135deg,#173F35,#2d6a4f);">
    <div class="container text-white">
        <div class="d-flex align-items-center justify-content-between">
            <div>
                <h2 class="fw-bold mb-1"><i class="bi bi-receipt me-2 text-warning"></i>Detail Transaksi</h2>
                <div class="text-warning fw-bold">{{ $transaksi->kode_transaksi }}</div>
            </div>
            <div class="text-end">
                {!! $transaksi->status_badge !!}
            </div>
        </div>
    </div>
</div>

<div class="py-5" style="background:#f8faf9;">
    <div class="container">

        @if(session('success'))
        <div class="alert alert-success border-0 rounded-3 d-flex align-items-center gap-2 mb-4">
            <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
        </div>
        @endif
        @if(session('error'))
        <div class="alert alert-danger border-0 rounded-3 d-flex align-items-center gap-2 mb-4">
            <i class="bi bi-exclamation-triangle-fill"></i> {{ session('error') }}
        </div>
        @endif

        <div class="row g-4">
            {{-- Main Info --}}
            <div class="col-lg-8">

                {{-- Items --}}
                <div class="card border-0 rounded-4 shadow-sm mb-4">
                    <div class="card-header border-0 p-4 rounded-4" style="background:white;border-bottom:2px solid #f0f0f0!important;">
                        <div class="fw-bold" style="color:#173F35;"><i class="bi bi-box-seam me-2"></i>Alat yang Disewa</div>
                    </div>
                    <div class="card-body p-4 pt-0">
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead class="small text-muted">
                                    <tr>
                                        <th>Alat</th>
                                        <th class="text-center">Jml</th>
                                        <th class="text-end">Harga/Hari</th>
                                        <th class="text-end">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($transaksi->detailTransaksi as $item)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-3">
                                                @if($item->alatHiking->gambar ?? false)
                                                    <img src="{{ Storage::url($item->alatHiking->gambar) }}"
                                                        class="rounded-3" style="width:45px;height:45px;object-fit:cover;">
                                                @else
                                                    <div class="rounded-3 bg-light d-flex align-items-center justify-content-center"
                                                        style="width:45px;height:45px;">
                                                        <i class="bi bi-box text-muted"></i>
                                                    </div>
                                                @endif
                                                <div>
                                                    <div class="fw-semibold small">{{ $item->alatHiking->nama_alat ?? '-' }}</div>
                                                    <div class="text-muted" style="font-size:0.72rem;">
                                                        {{ $item->alatHiking->kategori->nama_kategori ?? '' }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center small">{{ $item->jumlah }}</td>
                                        <td class="text-end small">Rp {{ number_format($item->harga_per_hari_snapshot, 0, ',', '.') }}</td>
                                        <td class="text-end fw-semibold small" style="color:#173F35;">
                                            Rp {{ number_format($item->harga_per_hari_snapshot * $item->jumlah * $transaksi->total_hari, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Periode & Metode --}}
                <div class="card border-0 rounded-4 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <div class="fw-bold mb-3" style="color:#173F35;"><i class="bi bi-calendar3 me-2"></i>Detail Sewa</div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="p-3 rounded-3" style="background:#f8faf9;">
                                    <div class="small text-muted mb-1">Tanggal Sewa</div>
                                    <div class="fw-semibold">{{ \Carbon\Carbon::parse($transaksi->tgl_sewa)->format('d F Y') }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3 rounded-3" style="background:#f8faf9;">
                                    <div class="small text-muted mb-1">Tanggal Kembali</div>
                                    <div class="fw-semibold">{{ \Carbon\Carbon::parse($transaksi->tgl_kembali)->format('d F Y') }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3 rounded-3" style="background:#f8faf9;">
                                    <div class="small text-muted mb-1">Durasi</div>
                                    <div class="fw-semibold">{{ $transaksi->total_hari }} hari</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3 rounded-3" style="background:#f8faf9;">
                                    <div class="small text-muted mb-1">Metode Pengambilan</div>
                                    <div class="fw-semibold">
                                        <i class="bi {{ $transaksi->metode_pengambilan=='Kirim via Kurir'?'bi-truck':'bi-shop' }} me-1 text-muted"></i>
                                        {{ $transaksi->metode_pengambilan }}
                                    </div>
                                </div>
                            </div>
                            @if($transaksi->alamat_pengiriman)
                            <div class="col-12">
                                <div class="p-3 rounded-3" style="background:#f8faf9;">
                                    <div class="small text-muted mb-1">Alamat Pengiriman</div>
                                    <div class="fw-semibold">{{ $transaksi->alamat_pengiriman }}</div>
                                </div>
                            </div>
                            @endif
                            @if($transaksi->catatan_customer)
                            <div class="col-12">
                                <div class="p-3 rounded-3 border-start border-warning border-3" style="background:#fffdf0;">
                                    <div class="small text-muted mb-1">Catatan Anda</div>
                                    <div class="small">{{ $transaksi->catatan_customer }}</div>
                                </div>
                            </div>
                            @endif
                            @if($transaksi->catatan_admin)
                            <div class="col-12">
                                <div class="p-3 rounded-3 border-start border-primary border-3" style="background:#f0f4ff;">
                                    <div class="small text-muted mb-1">Catatan Admin</div>
                                    <div class="small">{{ $transaksi->catatan_admin }}</div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Cancel button --}}
                @if($transaksi->status_transaksi == 'Pending')
                <div class="card border-0 rounded-4 shadow-sm border-danger" style="border:2px solid #f8d7da!important;">
                    <div class="card-body p-4">
                        <h6 class="fw-bold text-danger mb-2"><i class="bi bi-exclamation-triangle me-2"></i>Batalkan Pesanan</h6>
                        <p class="text-muted small mb-3">Pesanan yang masih berstatus <strong>Pending</strong> dapat dibatalkan. Pesanan yang sudah disetujui tidak dapat dibatalkan.</p>
                        <form action="{{ route('customer.transaksi.cancel', $transaksi->id_transaksi) }}" method="POST"
                            onsubmit="return confirm('Yakin ingin membatalkan pesanan ini?')">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-danger px-4">
                                <i class="bi bi-x-circle me-2"></i>Batalkan Pesanan
                            </button>
                        </form>
                    </div>
                </div>
                @endif
            </div>

            {{-- Sidebar --}}
            <div class="col-lg-4">
                {{-- Cost Summary --}}
                <div class="card border-0 rounded-4 shadow-sm mb-3">
                    <div class="card-body p-4">
                        <div class="fw-bold mb-3" style="color:#173F35;"><i class="bi bi-calculator me-2"></i>Rincian Biaya</div>
                        <div class="d-flex justify-content-between small mb-2">
                            <span class="text-muted">Subtotal Alat</span>
                            <span>Rp {{ number_format($transaksi->subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex justify-content-between small mb-2">
                            <span class="text-muted">Biaya Pengiriman</span>
                            <span>Rp {{ number_format($transaksi->biaya_pengiriman, 0, ',', '.') }}</span>
                        </div>
                        @if($transaksi->diskon > 0)
                        <div class="d-flex justify-content-between small mb-2 text-success">
                            <span>Diskon</span>
                            <span>- Rp {{ number_format($transaksi->diskon, 0, ',', '.') }}</span>
                        </div>
                        @endif
                        @if($transaksi->denda > 0)
                        <div class="d-flex justify-content-between small mb-2 text-danger">
                            <span>Denda Keterlambatan</span>
                            <span>+ Rp {{ number_format($transaksi->denda, 0, ',', '.') }}</span>
                        </div>
                        @endif
                        <hr>
                        <div class="d-flex justify-content-between fw-bold">
                            <span style="color:#173F35;">TOTAL</span>
                            <span style="color:#173F35;font-size:1.1rem;">Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                {{-- Status Timeline --}}
                <div class="card border-0 rounded-4 shadow-sm mb-3">
                    <div class="card-body p-4">
                        <div class="fw-bold mb-3" style="color:#173F35;"><i class="bi bi-activity me-2"></i>Status Pesanan</div>
                        @php
                            $statuses = ['Pending','Disetujui','Berjalan','Selesai'];
                            $currentIdx = array_search($transaksi->status_transaksi, $statuses);
                        @endphp
                        @foreach($statuses as $i => $st)
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                                style="width:36px;height:36px;
                                background:{{ ($currentIdx !== false && $i <= $currentIdx) ? '#173F35' : '#e0e0e0' }};
                                color:{{ ($currentIdx !== false && $i <= $currentIdx) ? 'white' : '#999' }};">
                                <i class="bi {{ $i <= ($currentIdx ?? -1) ? 'bi-check-lg' : 'bi-circle' }} small"></i>
                            </div>
                            <div class="small {{ $transaksi->status_transaksi == $st ? 'fw-bold' : 'text-muted' }}"
                                style="color:{{ $transaksi->status_transaksi == $st ? '#173F35' : '' }};">
                                {{ $st }}
                            </div>
                        </div>
                        @endforeach

                        @if(in_array($transaksi->status_transaksi, ['Ditolak','Dibatalkan']))
                        <div class="alert alert-danger border-0 rounded-3 small p-2 mt-2">
                            <i class="bi bi-x-circle me-1"></i>
                            Transaksi ini <strong>{{ $transaksi->status_transaksi }}</strong>
                        </div>
                        @endif
                    </div>
                </div>

                <a href="{{ route('customer.transaksi.index') }}" class="btn btn-outline-secondary w-100">
                    <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
                </a>
            </div>
        </div>
    </div>
</div>

@endsection

