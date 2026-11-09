@extends('layouts.app')

@section('content')
<div class="py-5" style="background-color: var(--off-white); min-height: 80vh;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8">
                <div class="card border-0 shadow rounded-4 p-4 p-md-5 bg-white">
                    <div class="text-center mb-4">
                        <i class="bi bi-person-plus-fill text-warning fs-1"></i>
                        <h3 class="fw-bold mt-2">Daftar Akun Pendaki</h3>
                        <p class="text-muted small">Buat akun untuk melakukan reservasi dan tracking status penyewaan alat.</p>
                    </div>

                    @if($errors->any())
                        <div class="alert alert-danger" role="alert">
                            <ul class="mb-0 small ps-3">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('register.post') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="nama_lengkap" class="form-control" placeholder="Contoh: Budi Santoso" value="{{ old('nama_lengkap') }}" required>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control" placeholder="nama@email.com" value="{{ old('email') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">No. HP/WhatsApp <span class="text-danger">*</span></label>
                                <input type="tel" name="no_hp" class="form-control" placeholder="081234567890" value="{{ old('no_hp') }}" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Alamat Lengkap</label>
                            <textarea name="alamat" rows="2" class="form-control" placeholder="Jl. Nama Jalan, Kota, Kode Pos">{{ old('alamat') }}</textarea>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Password <span class="text-danger">*</span></label>
                                <input type="password" name="password" class="form-control" placeholder="Minimal 8 karakter" minlength="8" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Konfirmasi Password <span class="text-danger">*</span></label>
                                <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi password (minimal 8 karakter)" minlength="8" required>
                            </div>
                        </div>

                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-brand py-2 fs-6 fw-semibold">
                                <i class="bi bi-person-plus me-2"></i> Daftar Akun Sekarang
                            </button>
                        </div>
                    </form>

                    <div class="text-center text-muted small mt-4">
                        Sudah punya akun HIKETRIP RENTAL?<br>
                        <a href="{{ route('login') }}" class="text-decoration-none fw-semibold text-success">
                            Masuk ke Akun <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
