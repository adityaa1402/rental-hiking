@extends('layouts.admin')

@section('title', 'Pesan Masuk')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h4 class="fw-bold mb-1">Pesan Masuk</h4>
        <small class="text-muted">Pesan dan pertanyaan yang dikirim melalui halaman kontak.</small>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4 p-3 bg-white mb-4">
    <form method="GET" class="row g-2 align-items-center">
        <div class="col-md-8">
            <input type="search" name="q" class="form-control form-control-sm"
                placeholder="Cari nama, email, atau subjek..." value="{{ request('q', '') }}">
        </div>
        <div class="col-md-4 d-flex gap-2">
            <button type="submit" class="btn btn-outline-primary btn-sm">
                <i class="bi bi-search me-1"></i> Cari
            </button>
            <a href="{{ route('admin.pesan.index') }}" class="btn btn-light btn-sm border">Reset</a>
        </div>
    </form>
</div>

<div class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light small text-muted">
                <tr>
                    <th class="ps-4">Pengirim</th>
                    <th>Subjek</th>
                    <th>Tanggal</th>
                    <th class="text-end pe-4">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pesanList as $pesan)
                    <tr>
                        <td class="ps-4">
                            <div class="fw-bold">{{ $pesan->nama }}</div>
                            <a class="small text-muted text-decoration-none" href="mailto:{{ $pesan->email }}">
                                {{ $pesan->email }}
                            </a>
                            @if($pesan->user)
                                <div><a class="small text-success" href="{{ route('admin.customer.show', $pesan->user->id) }}">Akun terdaftar</a></div>
                            @else
                                <div class="small text-muted">Pesan tamu</div>
                            @endif
                        </td>
                        <td>{{ $pesan->subjek }}</td>
                        <td class="small text-muted">{{ $pesan->created_at->format('d M Y, H:i') }}</td>
                        <td class="text-end pe-4">
                            <a href="{{ route('admin.pesan.show', $pesan->id_pesan) }}" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-eye me-1"></i> Lihat Pesan
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center py-5 text-muted">
                            Belum ada pesan yang masuk.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if($pesanList->hasPages())
    <div class="d-flex justify-content-center mt-4">
        {{ $pesanList->links('pagination::bootstrap-5') }}
    </div>
@endif
@endsection
