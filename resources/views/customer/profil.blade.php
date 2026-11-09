@extends('layouts.app')

@section('title', 'Profil Akun')

@section('content')

<div class="py-4" style="background:linear-gradient(135deg,#173F35,#2d6a4f);">
    <div class="container text-white">
        <h2 class="fw-bold mb-1"><i class="bi bi-person-gear me-2 text-warning"></i>Profil Akun</h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 small">
                <li class="breadcrumb-item"><a href="{{ route('customer.dashboard') }}" class="text-warning">Dashboard</a></li>
                <li class="breadcrumb-item active text-white-50">Profil</li>
            </ol>
        </nav>
    </div>
</div>

<div class="py-5" style="background:#f8faf9;">
    <div class="container">
        <div class="row g-4 justify-content-center">
            <div class="col-lg-8">

                {{-- Edit Profile --}}
                <div class="card border-0 rounded-4 shadow-sm mb-4">
                    <div class="card-header border-0 p-4" style="background:white;border-bottom:2px solid #f0f0f0!important;">
                        <div class="fw-bold" style="color:#173F35;"><i class="bi bi-person me-2"></i>Informasi Profil</div>
                    </div>
                    <div class="card-body p-4">
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

                        <form action="{{ route('customer.profil.update') }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label small fw-semibold text-muted">Nama Lengkap *</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-person text-muted"></i></span>
                                        <input type="text" name="nama_lengkap" class="form-control border-start-0 @error('nama_lengkap') is-invalid @enderror"
                                            value="{{ old('nama_lengkap', auth()->user()->nama_lengkap) }}" required>
                                    </div>
                                    @error('nama_lengkap')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold text-muted">Email *</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-envelope text-muted"></i></span>
                                        <input type="email" name="email" class="form-control border-start-0 @error('email') is-invalid @enderror"
                                            value="{{ old('email', auth()->user()->email) }}" required>
                                    </div>
                                    @error('email')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold text-muted">No. HP / WhatsApp</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-telephone text-muted"></i></span>
                                        <input type="text" name="no_hp" class="form-control border-start-0"
                                            value="{{ old('no_hp', auth()->user()->no_hp) }}">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label class="form-label small fw-semibold text-muted">Alamat</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-geo-alt text-muted"></i></span>
                                        <textarea name="alamat" class="form-control border-start-0" rows="2">{{ old('alamat', auth()->user()->alamat) }}</textarea>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-brand px-4 py-2">
                                        <i class="bi bi-save me-2"></i>Simpan Perubahan
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Change Password --}}
                <div class="card border-0 rounded-4 shadow-sm">
                    <div class="card-header border-0 p-4" style="background:white;border-bottom:2px solid #f0f0f0!important;">
                        <div class="fw-bold" style="color:#173F35;"><i class="bi bi-shield-lock me-2"></i>Ganti Password</div>
                    </div>
                    <div class="card-body p-4">
                        @if(session('password_success'))
                        <div class="alert alert-success border-0 rounded-3 d-flex align-items-center gap-2 mb-4">
                            <i class="bi bi-check-circle-fill"></i> {{ session('password_success') }}
                        </div>
                        @endif
                        <form action="{{ route('customer.profil.password') }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label small fw-semibold text-muted">Password Lama *</label>
                                    <input type="password" name="password_lama" class="form-control @error('password_lama') is-invalid @enderror"
                                        placeholder="Password saat ini">
                                    @error('password_lama')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold text-muted">Password Baru *</label>
                                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                                        placeholder="Min. 8 karakter" minlength="8">
                                    @error('password')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold text-muted">Konfirmasi Password Baru *</label>
                                    <input type="password" name="password_confirmation" class="form-control"
                                        placeholder="Ulangi password baru (min. 8 karakter)" minlength="8">
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-outline-danger px-4 py-2">
                                        <i class="bi bi-shield-lock me-2"></i>Ganti Password
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection
