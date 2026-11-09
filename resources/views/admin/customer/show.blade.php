@extends('layouts.admin')

@section('title', 'Detail Pelanggan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h4 class="fw-bold mb-1">{{ $customer->nama_lengkap }}</h4>
        <small class="text-muted">ID: {{ $customer->id }} • Terdaftar: {{ \Carbon\Carbon::parse($customer->created_at)->format('d/m/Y') }}</small>
    </div>
    <a href="{{ route('admin.customer.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Kembali
    </a>
</div>

<div class="row g-4">
    <!-- Kolom Kiri: Info Pelanggan -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
            <h6 class="fw-bold mb-3 border-bottom pb-2">Informasi Pribadi</h6>
            <div class="row g-2 small">
                <div class="col-12">
                    <div class="text-muted d-block mb-1">Email</div>
                    <div class="text-break">{{ $customer->email }}</div>
                </div>
                <div class="col-12">
                    <div class="text-muted d-block mb-1">Nomor Telepon</div>
                    <div>{{ $customer->no_hp ?? '-' }}</div>
                </div>
                <div class="col-12">
                    <div class="text-muted d-block mb-1">Alamat</div>
                    <div>{{ $customer->alamat ?? '-' }}</div>
                </div>
                <div class="col-12">
                    <div class="text-muted d-block mb-1">Status</div>
                    <span class="badge bg-success">Aktif</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Kolom Kanan: Riwayat Transaksi -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
            <h6 class="fw-bold mb-3 border-bottom pb-2">Riwayat Transaksi</h6>
            
            @if($customer->transaksi->count() > 0)
                <div class="table-responsive">
                    <table class="table table-sm table-hover mb-0">
                        <thead class="table-light small">
                            <tr>
                                <th>Kode</th>
                                <th>Periode</th>
                                <th>Total</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($customer->transaksi as $tx)
                                <tr>
                                    <td class="font-monospace">{{ $tx->id_transaksi }}</td>
                                    <td class="small">{{ \Carbon\Carbon::parse($tx->tgl_sewa)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($tx->tgl_kembali)->format('d/m/Y') }}</td>
                                    <td class="fw-bold text-success">{{ formatRupiah($tx->total_harga) }}</td>
                                    <td>
                                        @php
                                            $statusClass = match($tx->status_transaksi) {
                                                'Pending' => 'warning',
                                                'Disetujui' => 'info',
                                                'Berjalan' => 'primary',
                                                'Selesai' => 'success',
                                                'Ditolak' => 'danger',
                                                default => 'secondary'
                                            };
                                        @endphp
                                        <span class="badge bg-{{ $statusClass }} small">{{ $tx->status_transaksi }}</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info small mb-0">
                    <i class="bi bi-info-circle me-2"></i> Pelanggan ini belum memiliki transaksi.
                </div>
            @endif
        </div>
    </div>
</div>

@endsection
