<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') | Admin HIKETRIP RENTAL</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Space+Grotesk:wght@600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
    @stack('styles')
</head>
<body class="bg-light">

<div class="container-fluid p-0">
    <div class="row g-0">
        <!-- Sidebar Admin Desktop -->
        <aside class="col-lg-2 col-md-3 sidebar-admin d-none d-md-block p-3" style="min-height:100vh;">
            <div class="d-flex align-items-center gap-2 mb-4 px-2">
                <i class="bi bi-compass text-warning fs-3"></i>
                <div>
                    <h6 class="fw-bold text-white mb-0 font-monospace">HIKETRIP</h6>
                    <small class="text-white-50" style="font-size:0.75rem;">ADMIN PANEL</small>
                </div>
            </div>

            @php $pendingCount = \App\Models\Transaksi::where('status_transaksi','Pending')->count(); @endphp

            <div class="px-2 mb-2"><div class="small text-white-50 text-uppercase fw-semibold" style="font-size:0.7rem;letter-spacing:1px;">Menu Utama</div></div>
            <nav class="nav flex-column mb-4">
                <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                    <i class="bi bi-speedometer2"></i> Overview
                </a>
                <a class="nav-link {{ request()->routeIs('admin.barang.*') ? 'active' : '' }}" href="{{ route('admin.barang.index') }}">
                    <i class="bi bi-box-seam"></i> Data Barang
                </a>
                <a class="nav-link {{ request()->routeIs('admin.kategori.*') ? 'active' : '' }}" href="{{ route('admin.kategori.index') }}">
                    <i class="bi bi-tags"></i> Kategori
                </a>
                <a class="nav-link {{ request()->routeIs('admin.paket.*') ? 'active' : '' }}" href="{{ route('admin.paket.index') }}">
                    <i class="bi bi-boxes"></i> Paket Sewa
                </a>
                <a class="nav-link {{ request()->routeIs('admin.customer.*') ? 'active' : '' }}" href="{{ route('admin.customer.index') }}">
                    <i class="bi bi-people"></i> Data Customer
                </a>
            </nav>

            <div class="px-2 mb-2"><div class="small text-white-50 text-uppercase fw-semibold" style="font-size:0.7rem;letter-spacing:1px;">Operasional</div></div>
            <nav class="nav flex-column mb-4">
                <a class="nav-link d-flex justify-content-between align-items-center {{ request()->routeIs('admin.transaksi.*') ? 'active' : '' }}" href="{{ route('admin.transaksi.index') }}">
                    <span><i class="bi bi-receipt"></i> Transaksi</span>
                    @if($pendingCount > 0)
                        <span class="badge bg-warning text-dark">{{ $pendingCount }}</span>
                    @endif
                </a>
                <a class="nav-link {{ request()->routeIs('admin.pengembalian.*') ? 'active' : '' }}" href="{{ route('admin.pengembalian.index') }}">
                    <i class="bi bi-arrow-return-left"></i> Pengembalian
                </a>
                <a class="nav-link {{ request()->routeIs('admin.laporan.*') ? 'active' : '' }}" href="{{ route('admin.laporan.index') }}">
                    <i class="bi bi-file-earmark-bar-graph"></i> Laporan
                </a>
                <a class="nav-link {{ request()->routeIs('admin.pengaturan.*') ? 'active' : '' }}" href="{{ route('admin.pengaturan.index') }}">
                    <i class="bi bi-gear"></i> Pengaturan
                </a>
            </nav>

            <div class="pt-3 border-top border-secondary border-opacity-25">
                <a href="{{ route('home') }}" target="_blank" class="nav-link text-white-50 small mb-1">
                    <i class="bi bi-globe"></i> Lihat Website Publik
                </a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="nav-link text-danger small border-0 bg-transparent w-100 text-start">
                        <i class="bi bi-box-arrow-right"></i> Keluar (Logout)
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="col-lg-10 col-md-9 d-flex flex-column" style="min-height:100vh;">
            <!-- Topbar -->
            <header class="navbar navbar-expand bg-white border-bottom px-4 py-3 sticky-top no-print">
                <div class="container-fluid p-0">
                    <div class="d-md-none me-3">
                        <button class="btn btn-outline-secondary btn-sm" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasAdmin">
                            <i class="bi bi-list fs-5"></i>
                        </button>
                    </div>
                    <div class="d-none d-sm-block">
                        <h6 class="mb-0 fw-bold text-dark">@yield('title', 'Dashboard')</h6>
                        <small class="text-muted">Panel Administrator HIKETRIP RENTAL</small>
                    </div>
                    <div class="ms-auto d-flex align-items-center gap-3">
                        <span class="badge bg-success-subtle text-success border border-success px-3 py-2">
                            <i class="bi bi-person-badge-fill me-1"></i> {{ auth()->user()->nama_lengkap }}
                        </span>
                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger btn-sm" title="Logout">
                                <i class="bi bi-box-arrow-right"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <!-- Offcanvas Mobile -->
            <div class="offcanvas offcanvas-start sidebar-admin text-white" tabindex="-1" id="offcanvasAdmin">
                <div class="offcanvas-header border-bottom border-secondary border-opacity-25">
                    <h5 class="offcanvas-title text-white fw-bold font-monospace">HIKETRIP ADMIN</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
                </div>
                <div class="offcanvas-body p-3">
                    <nav class="nav flex-column gap-1">
                        <a class="nav-link" href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2"></i> Overview</a>
                        <a class="nav-link" href="{{ route('admin.barang.index') }}"><i class="bi bi-box-seam"></i> Data Barang</a>
                        <a class="nav-link" href="{{ route('admin.kategori.index') }}"><i class="bi bi-tags"></i> Kategori</a>
                        <a class="nav-link" href="{{ route('admin.paket.index') }}"><i class="bi bi-boxes"></i> Paket Sewa</a>
                        <a class="nav-link" href="{{ route('admin.customer.index') }}"><i class="bi bi-people"></i> Customer</a>
                        <a class="nav-link" href="{{ route('admin.transaksi.index') }}"><i class="bi bi-receipt"></i> Transaksi</a>
                        <a class="nav-link" href="{{ route('admin.pengembalian.index') }}"><i class="bi bi-arrow-return-left"></i> Pengembalian</a>
                        <a class="nav-link" href="{{ route('admin.laporan.index') }}"><i class="bi bi-file-earmark-bar-graph"></i> Laporan</a>
                        <a class="nav-link" href="{{ route('admin.pengaturan.index') }}"><i class="bi bi-gear"></i> Pengaturan</a>
                        <hr class="border-secondary">
                        <a class="nav-link" href="{{ route('home') }}" target="_blank"><i class="bi bi-globe"></i> Website Publik</a>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="nav-link text-danger border-0 bg-transparent w-100 text-start">
                                <i class="bi bi-box-arrow-right"></i> Logout
                            </button>
                        </form>
                    </nav>
                </div>
            </div>

            <!-- Content -->
            <main class="p-4 flex-grow-1">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show d-flex align-items-center mb-4" role="alert">
                        <i class="bi bi-check-circle-fill fs-5 me-2"></i>
                        <div>{{ session('success') }}</div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center mb-4" role="alert">
                        <i class="bi bi-exclamation-triangle-fill fs-5 me-2"></i>
                        <div>{{ session('error') }}</div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('assets/js/main.js') }}"></script>
@stack('scripts')
</body>
</html>

