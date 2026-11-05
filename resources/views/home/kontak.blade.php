@extends('layouts.app')

@section('title', 'Kontak Kami')

@section('content')

{{-- Page Header --}}
<div class="py-5" style="background:linear-gradient(135deg,#173F35,#2d6a4f);">
    <div class="container text-center text-white py-3">
        <h1 class="fw-bold mb-2"><i class="bi bi-envelope-heart me-2 text-warning"></i>Kontak Kami</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-center mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-warning">Beranda</a></li>
                <li class="breadcrumb-item active text-white-50">Kontak</li>
            </ol>
        </nav>
    </div>
</div>

<section class="py-5 bg-white">
    <div class="container">
        <div class="row g-5">
            {{-- Contact Info --}}
            <div class="col-lg-5">
                <h3 class="fw-bold mb-4" style="color:#173F35;">Informasi Kontak</h3>
                <div class="d-flex flex-column gap-3 mb-5">
                    <div class="d-flex align-items-start gap-3 p-4 rounded-3" style="background:#e8f5e9;">
                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                            style="width:50px;height:50px;background:#173F35;">
                            <i class="bi bi-geo-alt-fill text-warning fs-5"></i>
                        </div>
                        <div>
                            <div class="fw-semibold mb-1" style="color:#173F35;">Alamat Toko</div>
                            <div class="text-muted small lh-base">{{ $setting->alamat ?? 'Jl. Pendakian No. 1, Kota' }}</div>
                        </div>
                    </div>
                    <div class="d-flex align-items-start gap-3 p-4 rounded-3" style="background:#e8f5e9;">
                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                            style="width:50px;height:50px;background:#173F35;">
                            <i class="bi bi-whatsapp text-warning fs-5"></i>
                        </div>
                        <div>
                            <div class="fw-semibold mb-1" style="color:#173F35;">WhatsApp</div>
                            <div class="text-muted small">{{ $setting->whatsapp ?? '-' }}</div>
                            @if($setting->whatsapp ?? false)
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/','',$setting->whatsapp) }}"
                                target="_blank" class="btn btn-success btn-sm mt-2 rounded-pill">
                                <i class="bi bi-whatsapp me-1"></i> Chat Sekarang
                            </a>
                            @endif
                        </div>
                    </div>
                    <div class="d-flex align-items-start gap-3 p-4 rounded-3" style="background:#e8f5e9;">
                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                            style="width:50px;height:50px;background:#173F35;">
                            <i class="bi bi-clock-fill text-warning fs-5"></i>
                        </div>
                        <div>
                            <div class="fw-semibold mb-1" style="color:#173F35;">Jam Operasional</div>
                            <div class="text-muted small">{{ $setting->jam_operasional ?? 'Senin - Sabtu, 08.00 - 20.00 WIB' }}</div>
                        </div>
                    </div>
                    <div class="d-flex align-items-start gap-3 p-4 rounded-3" style="background:#e8f5e9;">
                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                            style="width:50px;height:50px;background:#173F35;">
                            <i class="bi bi-envelope-fill text-warning fs-5"></i>
                        </div>
                        <div>
                            <div class="fw-semibold mb-1" style="color:#173F35;">Email</div>
                            <div class="text-muted small">{{ $setting->email ?? '-' }}</div>
                        </div>
                    </div>
                </div>

                {{-- Quick Actions --}}
                <div class="p-4 rounded-4 text-center" style="background:linear-gradient(135deg,#173F35,#2d6a4f);">
                    <i class="bi bi-headset text-warning fs-2 mb-2 d-block"></i>
                    <h5 class="text-white fw-bold mb-2">Butuh Bantuan Cepat?</h5>
                    <p class="text-white-50 small mb-3">Kami siap melayani pertanyaan Anda 7 hari seminggu.</p>
                    @if($setting->whatsapp ?? false)
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/','',$setting->whatsapp) }}?text=Halo, saya ingin menanyakan tentang rental alat hiking."
                        target="_blank" class="btn btn-warning fw-bold w-100">
                        <i class="bi bi-whatsapp me-2"></i> WhatsApp Kami
                    </a>
                    @endif
                </div>
            </div>

            {{-- Contact Form --}}
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm rounded-4 p-4">
                    <h3 class="fw-bold mb-1" style="color:#173F35;">Kirim Pesan</h3>
                    <p class="text-muted small mb-4">Kami akan membalas dalam 1x24 jam.</p>

                    @if(session('success'))
                        <div class="alert alert-success border-0 rounded-3 d-flex align-items-center gap-2">
                            <i class="bi bi-check-circle-fill"></i>
                            {{ session('success') }}
                        </div>
                    @endif

                    <form>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold text-muted">Nama Lengkap *</label>
                                <input type="text" class="form-control" placeholder="Nama Anda"
                                    value="{{ auth()->user()->nama_lengkap ?? '' }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold text-muted">Email *</label>
                                <input type="email" class="form-control" placeholder="email@contoh.com"
                                    value="{{ auth()->user()->email ?? '' }}">
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-semibold text-muted">No. WhatsApp</label>
                                <input type="text" class="form-control" placeholder="08123456789"
                                    value="{{ auth()->user()->no_hp ?? '' }}">
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-semibold text-muted">Subjek *</label>
                                <select class="form-select">
                                    <option value="">-- Pilih Subjek --</option>
                                    <option>Pertanyaan tentang produk</option>
                                    <option>Pertanyaan tentang sewa</option>
                                    <option>Keluhan / saran</option>
                                    <option>Informasi ketersediaan stok</option>
                                    <option>Lainnya</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-semibold text-muted">Pesan *</label>
                                <textarea class="form-control" rows="5" placeholder="Tuliskan pesan Anda di sini..."></textarea>
                            </div>
                            <div class="col-12">
                                <div class="p-3 bg-light rounded-3 small text-muted mb-3">
                                    <i class="bi bi-info-circle me-1 text-primary"></i>
                                    Untuk respons lebih cepat, kami menyarankan menghubungi via WhatsApp.
                                </div>
                                <button type="button" class="btn btn-brand w-100 py-3" onclick="alert('Pesan terkirim! Kami akan segera merespons.')">
                                    <i class="bi bi-send me-2"></i> Kirim Pesan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

