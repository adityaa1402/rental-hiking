@extends('layouts.app')

@php
    $shopSettings = getShopSettings();
@endphp

@section('content')
<!-- Header Banner -->
<div class="py-5" style="background: linear-gradient(135deg, #102D27, #173F35); color: #ffffff;">
    <div class="container text-center">
        <span class="badge bg-warning text-dark px-3 py-2 mb-2 fw-bold">PROFIL PERUSAHAAN</span>
        <h1 class="fw-bold mb-2">Tentang HIKETRIP RENTAL</h1>
        <p class="lead text-white-50 mx-auto" style="max-width: 650px;">
            Mitra tepercaya para penggiat alam terbuka dalam menyediakan perlengkapan pendakian gunung yang aman, higienis, dan teruji.
        </p>
    </div>
</div>

<main class="py-5">
    <div class="container">
        <div class="row align-items-center g-5 mb-5">
            <div class="col-lg-6">
                <span class="text-uppercase fw-bold text-success small tracking-wider">Kisah Kami</span>
                <h2 class="fw-bold text-dark mt-1 mb-3">Mendukung Petualangan Aman Setiap Pendaki</h2>
                <p class="text-muted">
                    HIKETRIP RENTAL didirikan dengan satu misi sederhana: <strong>membuat kegiatan pendakian gunung lebih inklusif, aman, dan terjangkau</strong>. Kami menyadari bahwa membeli perlengkapan ekspedisi berkualitas memerlukan investasi yang besar, terutama bagi pendaki pemula atau mereka yang hanya melakukan pendakian beberapa kali dalam setahun.
                </p>
                <p class="text-muted">
                    Melalui sistem penyewaan kami yang terawat, setiap orang dapat menikmati keindahan puncak-puncak gunung di Nusantara dengan peralatan berspesifikasi tinggi dari brand outdoor terkemuka seperti Eiger, Consina, Naturehike, Osprey, Deuter, dan Black Diamond.
                </p>
                <div class="row g-3 pt-2">
                    <div class="col-sm-6">
                        <div class="p-3 bg-white rounded-3 border shadow-sm">
                            <h4 class="fw-bold text-success mb-1">100%</h4>
                            <small class="text-muted">QC Kebersihan & Kelayakan Sebelum Diserahkan</small>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="p-3 bg-white rounded-3 border shadow-sm">
                            <h4 class="fw-bold text-warning mb-1">24/7</h4>
                            <small class="text-muted">Layanan Konsultasi Pemilihan Gear Pendakian</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden p-4 bg-white">
                    <h5 class="fw-bold mb-3"><i class="bi bi-compass text-warning me-2"></i>Komitmen Layanan HIKETRIP</h5>
                    <div class="d-flex gap-3 mb-3">
                        <div class="bg-success bg-opacity-10 text-success p-3 rounded-circle fs-4 h-100">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1">Standar Keselamatan (Safety First)</h6>
                            <p class="small text-muted mb-0">Tenda tidak bocor, frame tidak bengkok, dan sleeping bag memiliki batas suhu nyata yang sesuai label pabrikan.</p>
                        </div>
                    </div>
                    <div class="d-flex gap-3 mb-3">
                        <div class="bg-warning bg-opacity-10 text-warning p-3 rounded-circle fs-4 h-100">
                            <i class="bi bi-recycle"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1">Prinsip Ramah Lingkungan</h6>
                            <p class="small text-muted mb-0">Mendukung gaya hidup <em>Leave No Trace</em> dan ekonomi sirkular dengan merawat gear agar berumur panjang.</p>
                        </div>
                    </div>
                    <div class="d-flex gap-3">
                        <div class="bg-primary bg-opacity-10 text-primary p-3 rounded-circle fs-4 h-100">
                            <i class="bi bi-currency-dollar"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1">Tarif Kompetitif & Jujur</h6>
                            <p class="small text-muted mb-0">Penghitungan hari sewa dilakukan secara transparan dan diverifikasi otomatis oleh sistem backend kami.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alamat Basecamp -->
        <div class="card border-0 bg-light p-4 p-md-5 rounded-4 shadow-sm text-center">
            <h4 class="fw-bold mb-2">Basecamp & Outlet Resmi</h4>
            <p class="text-muted mb-4">{!! nl2br(e($shopSettings['alamat'])) !!}</p>
            <div class="d-flex justify-content-center gap-3 flex-wrap">
                <span class="badge bg-white text-dark border p-2"><i class="bi bi-clock me-1 text-warning"></i> {{ $shopSettings['jam_operasional'] }}</span>
                <span class="badge bg-white text-dark border p-2"><i class="bi bi-telephone me-1 text-success"></i> {{ $shopSettings['telepon'] }}</span>
                <span class="badge bg-white text-dark border p-2"><i class="bi bi-envelope me-1 text-primary"></i> {{ $shopSettings['email'] }}</span>
            </div>
        </div>
    </div>
</main>
@endsection
