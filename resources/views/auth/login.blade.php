@extends('layouts.app')

@section('content')
<div class="py-5" style="background-color: var(--off-white); min-height: 80vh;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-7">
                <div class="card border-0 shadow rounded-4 p-4 p-md-5 bg-white">
                    <div class="text-center mb-4">
                        <i class="bi bi-compass text-warning display-4"></i>
                        <h3 class="fw-bold mt-2">Selamat Datang</h3>
                        <p class="text-muted small">Silakan masuk ke akun HIKETRIP RENTAL Anda.</p>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success d-flex align-items-center mb-4" role="alert">
                            <i class="bi bi-check-circle-fill fs-5 me-2"></i>
                            <div class="small">{{ session('success') }}</div>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger d-flex align-items-center mb-4" role="alert">
                            <i class="bi bi-exclamation-triangle-fill fs-5 me-2"></i>
                            <div class="small">
                                @foreach($errors->all() as $error)
                                    {{ $error }}
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login.post') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Alamat Email</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted"><i class="bi bi-envelope"></i></span>
                                <input type="email" name="email" class="form-control" placeholder="nama@email.com" value="{{ old('email') }}" required autofocus>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label small fw-semibold">Kata Sandi</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted"><i class="bi bi-lock"></i></span>
                                <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
                            </div>
                        </div>

                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-brand py-2 fs-6 fw-semibold">
                                <i class="bi bi-box-arrow-in-right me-2"></i> Masuk ke Dashboard
                            </button>
                        </div>
                    </form>

                    <div class="text-center text-muted small mt-4">
                        Belum punya akun HIKETRIP RENTAL?<br>
                        <a href="{{ route('register') }}" class="text-decoration-none fw-semibold text-success">
                            Daftar Akun Baru <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>

                <!-- Demo Credentials -->
                <div class="card border-0 bg-white bg-opacity-50 rounded-4 p-3 mt-3 small text-center text-muted">
                    <strong>Demo Login:</strong><br>
                    <strong>Admin:</strong> admin@hikettrip.com / admin123<br>
                    <strong>Customer:</strong> customer@hikettrip.com / customer123
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
