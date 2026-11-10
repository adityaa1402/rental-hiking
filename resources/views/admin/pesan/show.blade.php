@extends('layouts.admin')

@section('title', 'Detail Pesan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h4 class="fw-bold mb-1">Detail Pesan</h4>
        <small class="text-muted">Pesan yang dikirim melalui halaman kontak.</small>
    </div>
    <a href="{{ route('admin.pesan.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Kembali ke Pesan Masuk
    </a>
</div>

<div class="card border-0 shadow-sm rounded-4 bg-white">
    <div class="card-body p-4 p-md-5">
        <h5 class="fw-bold mb-4">{{ $pesan->subjek }}</h5>

        <dl class="row mb-4">
            <dt class="col-sm-3 text-muted">Nama</dt>
            <dd class="col-sm-9">{{ $pesan->nama }}</dd>

            <dt class="col-sm-3 text-muted">Email</dt>
            <dd class="col-sm-9"><a href="mailto:{{ $pesan->email }}">{{ $pesan->email }}</a></dd>

            <dt class="col-sm-3 text-muted">Akun</dt>
            <dd class="col-sm-9">
                @if($pesan->user)
                    <a href="{{ route('admin.customer.show', $pesan->user->id) }}">{{ $pesan->user->nama_lengkap }}</a>
                @else
                    Pengunjung (belum masuk)
                @endif
            </dd>

            <dt class="col-sm-3 text-muted">Dikirim</dt>
            <dd class="col-sm-9">{{ $pesan->created_at->format('d M Y, H:i') }}</dd>
        </dl>

        <hr>
        <h6 class="fw-bold mb-3">Isi Pesan</h6>
        <p class="mb-0 text-break" style="white-space: pre-wrap;">{{ $pesan->pesan }}</p>
    </div>
</div>
@endsection
