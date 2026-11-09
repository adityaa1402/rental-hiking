@php
    $currentRoute = request()->route() ? request()->route()->getName() : '';
    $shopSettings = getShopSettings();
@endphp

<nav class="navbar navbar-expand-lg navbar-dark navbar-custom sticky-top py-3">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}">
            <i class="bi bi-compass text-warning fs-3"></i>
            <span>HIKETRIP</span> RENTAL
        </a>
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain" aria-controls="navbarMain" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarMain">
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ route('home') }}">
                        <i class="bi bi-house-door me-1"></i> Beranda
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('katalog*') ? 'active' : '' }}" href="{{ route('katalog.index') }}">
                        <i class="bi bi-grid me-1"></i> Katalog Alat
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('paket*') ? 'active' : '' }}" href="{{ route('paket.index') }}">
                        <i class="bi bi-boxes me-1"></i> Paket Sewa
                    </a>
                </li>
                @auth
                    @if(auth()->user()->role === 'customer')
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('customer.transaksi.*') ? 'active' : '' }}" href="{{ route('customer.transaksi.index') }}">
                                <i class="bi bi-receipt me-1"></i> Pesanan
                            </a>
                        </li>
                    @endif
                @endauth
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('tentang') ? 'active' : '' }}" href="{{ route('tentang') }}">
                        <i class="bi bi-info-circle me-1"></i> Tentang Kami
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('kontak') ? 'active' : '' }}" href="{{ route('kontak') }}">
                        <i class="bi bi-envelope me-1"></i> Kontak
                    </a>
                </li>
            </ul>

            <div class="d-flex align-items-center gap-2">
                @auth
                    @if(auth()->user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-warning btn-sm px-3 fw-bold">
                            <i class="bi bi-speedometer2 me-1"></i> Dashboard Admin
                        </a>
                    @else
                        <div class="dropdown">
                            <button class="btn btn-outline-light btn-sm dropdown-toggle px-3 py-2 fw-semibold rounded-3 d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-person-circle fs-6 text-warning"></i>
                                <span>{{ auth()->user()->nama_lengkap }}</span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-2">
                                <li>
                                    <div class="px-3 py-1 text-muted small border-bottom mb-2">
                                        Role: <span class="badge bg-success">Customer</span>
                                    </div>
                                </li>
                                <li><a class="dropdown-item py-2" href="{{ route('customer.dashboard') }}"><i class="bi bi-grid-fill me-2 text-primary"></i> Dashboard</a></li>
                                <li><a class="dropdown-item py-2" href="{{ route('customer.transaksi.index') }}"><i class="bi bi-receipt-cutoff me-2 text-primary"></i> Riwayat Sewa</a></li>
                                <li><a class="dropdown-item py-2" href="{{ route('customer.profil.index') }}"><i class="bi bi-person-gear me-2 text-primary"></i> Profil Akun</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="dropdown-item py-2 text-danger">
                                            <i class="bi bi-box-arrow-right me-2"></i> Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @endif
                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger btn-sm px-3 ms-1" title="Keluar">
                            <i class="bi bi-box-arrow-right"></i>
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline-light btn-sm px-3">
                        <i class="bi bi-box-arrow-in-right me-1"></i> Masuk
                    </a>
                    <a href="{{ route('register') }}" class="btn btn-brand btn-sm px-3">
                        <i class="bi bi-person-plus me-1"></i> Daftar
                    </a>
                @endauth
            </div>
        </div>
    </div>
</nav>
