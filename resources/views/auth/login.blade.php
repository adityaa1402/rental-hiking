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
                                <input type="password" name="password" id="login-password" class="form-control" placeholder="Masukkan password" autocomplete="current-password" required>
                                <button type="button" class="btn btn-outline-secondary" id="toggle-login-password"
                                    aria-label="Tampilkan password" aria-pressed="false" aria-controls="login-password">
                                    <i class="bi bi-eye" aria-hidden="true"></i>
                                </button>
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

            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const loginPassword = document.getElementById('login-password');
    const toggleLoginPassword = document.getElementById('toggle-login-password');

    toggleLoginPassword.addEventListener('click', () => {
        const isPasswordVisible = loginPassword.type === 'text';

        loginPassword.type = isPasswordVisible ? 'password' : 'text';
        toggleLoginPassword.setAttribute('aria-pressed', String(!isPasswordVisible));
        toggleLoginPassword.setAttribute(
            'aria-label',
            isPasswordVisible ? 'Tampilkan password' : 'Sembunyikan password'
        );
        toggleLoginPassword.innerHTML = `<i class="bi ${isPasswordVisible ? 'bi-eye' : 'bi-eye-slash'}" aria-hidden="true"></i>`;
    });
</script>
@endpush
