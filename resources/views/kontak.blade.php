@extends('layouts.app')

@php
    $shopSettings = getShopSettings();
@endphp

@section('content')
<!-- Header Banner -->
<div class="py-4" style="background-color: var(--forest-green); color: #ffffff;">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-1">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white-50 text-decoration-none">Beranda</a></li>
                <li class="breadcrumb-item active text-white" aria-current="page">Kontak</li>
            </ol>
        </nav>
        <h2 class="fw-bold mb-0">Hubungi HIKETRIP RENTAL</h2>
        <p class="text-white-50 small mb-0">Ada pertanyaan seputar ketersediaan perlengkapan atau konsultasi rute pendakian? Hubungi tim kami.</p>
    </div>
</div>

<main class="py-5">
    <div class="container">
        <div class="row g-5">
            <!-- Informasi Kontak Toko -->
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 bg-white h-100">
                    <h4 class="fw-bold mb-4">Informasi Basecamp</h4>

                    <div class="d-flex align-items-start gap-3 mb-4">
                        <div class="bg-success bg-opacity-10 text-success p-3 rounded-circle fs-5">
                            <i class="bi bi-geo-alt-fill"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1">Alamat Outlet</h6>
                            <p class="text-muted small mb-0">{!! nl2br(e($shopSettings['alamat'])) !!}</p>
                        </div>
                    </div>

                    <div class="d-flex align-items-start gap-3 mb-4">
                        <div class="bg-warning bg-opacity-10 text-warning p-3 rounded-circle fs-5">
                            <i class="bi bi-whatsapp"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1">WhatsApp & Konsultasi Gear</h6>
                            <p class="text-muted small mb-1">{{ $shopSettings['whatsapp'] }}</p>
                            <span class="badge bg-success-subtle text-success small">Respon Cepat Jam Kerja</span>
                        </div>
                    </div>

                    <div class="d-flex align-items-start gap-3 mb-4">
                        <div class="bg-primary bg-opacity-10 text-primary p-3 rounded-circle fs-5">
                            <i class="bi bi-clock-fill"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1">Jam Operasional</h6>
                            <p class="text-muted small mb-0">{{ $shopSettings['jam_operasional'] }}</p>
                        </div>
                    </div>

                    <div class="d-flex align-items-start gap-3">
                        <div class="bg-secondary bg-opacity-10 text-secondary p-3 rounded-circle fs-5">
                            <i class="bi bi-envelope-fill"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1">Email Layanan</h6>
                            <p class="text-muted small mb-0">{{ $shopSettings['email'] }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Kontak -->
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 bg-white">
                    <h4 class="fw-bold mb-2">Kirim Pesan atau Pertanyaan</h4>
                    <p class="text-muted small mb-4">Staf kami akan menindaklanjuti pesan Anda dalam waktu maksimal 1x24 jam.</p>

                    @if(session('success'))
                        <div class="alert alert-success d-flex align-items-center mb-4" role="alert">
                            <i class="bi bi-check-circle-fill fs-4 me-3"></i>
                            <div>
                                <strong>Pesan Anda Berhasil Terkirim!</strong><br>
                                Terima kasih, tim HIKETRIP RENTAL akan segera menghubungi Anda kembali melalui email atau WhatsApp.
                            </div>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger d-flex align-items-center mb-4" role="alert">
                            <i class="bi bi-exclamation-triangle-fill fs-4 me-3"></i>
                            <div>
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('kontak.send') }}">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" name="nama" class="form-control" placeholder="Contoh: Budi Santoso" value="{{ old('nama', auth()->user()?->nama_lengkap) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Alamat Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control" placeholder="nama@email.com" value="{{ old('email', auth()->user()?->email) }}" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-semibold">Subjek Pesan <span class="text-danger">*</span></label>
                                <input type="text" name="subjek" class="form-control" placeholder="Pertanyaan sewa paket / ketersediaan barang" value="{{ old('subjek') }}" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-semibold">Isi Pesan <span class="text-danger">*</span></label>
                                <textarea name="pesan" rows="5" class="form-control" placeholder="Tuliskan pertanyaan atau kebutuhan pendakian Anda di sini..." required>{{ old('pesan') }}</textarea>
                            </div>
                            <div class="col-12 mt-4">
                                <button type="submit" class="btn btn-brand px-4 py-2">
                                    <i class="bi bi-send-fill me-2"></i> Kirim Pesan Sekarang
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
