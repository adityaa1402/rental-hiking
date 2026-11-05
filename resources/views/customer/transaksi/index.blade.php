@extends('layouts.app')

@section('title', 'Riwayat Transaksi')

@section('content')

<div class="py-4" style="background:linear-gradient(135deg,#173F35,#2d6a4f);">
    <div class="container text-white">
        <h2 class="fw-bold mb-1"><i class="bi bi-receipt-cutoff me-2 text-warning"></i>Riwayat Transaksi</h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 small">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-warning">Beranda</a></li>
                <li class="breadcrumb-item"><a href="{{ route('customer.dashboard') }}" class="text-warning">Dashboard</a></li>
                <li class="breadcrumb-item active text-white-50">Riwayat Transaksi</li>
            </ol>
        </nav>
    </div>
</div>

<div class="py-5" style="background:#f8faf9;">
    <div class="container">

        {{-- Filter --}}
        <div class="card border-0 rounded-4 shadow-sm mb-4">
            <div class="card-body p-4">
                <form method="GET" class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold text-muted">Cari Kode Transaksi</label>
                        <input type="text" name="q" class="form-control" placeholder="HKT-..." value="{{ request('q') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold text-muted">Filter Status</label>
                        <select name="status" class="form-select">
                            <option value="">Semua Status</option>
                            @foreach(['Pending','Disetujui','Berjalan','Selesai','Ditolak','Dibatalkan'] as $s)
                            <option value="{{ $s }}" {{ request('status')==$s?'selected':'' }}>{{ $s }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-brand me-2">
                            <i class="bi bi-search me-1"></i> Filter
                        </button>
                        @if(request()->hasAny(['q','status']))
                        <a href="{{ route('customer.transaksi.index') }}" class="btn btn-outline-secondary">Reset</a>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <div class="card border-0 rounded-4 shadow-sm">
            <div class="card-body p-4">
                @if($transaksis->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead style="background:#f8faf9;">
                            <tr class="small text-muted">
                                <th>Kode Transaksi</th>
                                <th>Tanggal Sewa</th>
                                <th>Durasi</th>
                                <th>Total Harga</th>
                                <th>Metode</th>
                                <th>Status</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transaksis as $trx)
                            <tr>
                                <td>
                                    <div class="fw-bold" style="color:#173F35;font-size:0.9rem;">{{ $trx->kode_transaksi }}</div>
                                    <div class="text-muted" style="font-size:0.72rem;">{{ $trx->created_at->format('d/m/Y H:i') }}</div>
                                </td>
                                <td class="small">
                                    <div>{{ \Carbon\Carbon::parse($trx->tgl_sewa)->format('d M Y') }}</div>
                                    <div class="text-muted">s/d {{ \Carbon\Carbon::parse($trx->tgl_kembali)->format('d M Y') }}</div>
                                </td>
                                <td class="text-center">
                                    <span class="badge rounded-pill" style="background:#e8f5e9;color:#173F35;">
                                        {{ $trx->total_hari }} hari
                                    </span>
                                </td>
                                <td class="fw-semibold small" style="color:#173F35;">
                                    Rp {{ number_format($trx->total_harga, 0, ',', '.') }}
                                </td>
                                <td class="small">
                                    <i class="bi {{ $trx->metode_pengambilan=='Kirim via Kurir'?'bi-truck':'bi-shop' }} me-1 text-muted"></i>
                                    {{ $trx->metode_pengambilan }}
                                </td>
                                <td>{!! $trx->status_badge !!}</td>
                                <td class="text-center">
                                    <a href="{{ route('customer.transaksi.show', $trx->id_transaksi) }}"
                                        class="btn btn-sm btn-outline-primary rounded-pill" style="font-size:0.75rem;">
                                        <i class="bi bi-eye me-1"></i>Detail
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $transaksis->appends(request()->query())->links() }}
                </div>
                @else
                <div class="text-center py-5">
                    <i class="bi bi-receipt text-muted mb-3 d-block" style="font-size:4rem;"></i>
                    <h5 class="fw-bold text-muted">Belum ada transaksi</h5>
                    <p class="text-muted small">
                        @if(request()->hasAny(['q','status']))
                            Tidak ditemukan transaksi dengan filter ini.
                        @else
                            Anda belum pernah menyewa. Yuk mulai petualangan!
                        @endif
                    </p>
                    <a href="{{ route('booking.create') }}" class="btn btn-brand px-4">
                        <i class="bi bi-bag-plus me-1"></i> Sewa Sekarang
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection

