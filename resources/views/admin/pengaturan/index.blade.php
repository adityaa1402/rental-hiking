@extends('layouts.admin')

@section('title', 'Pengaturan Toko')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Pengaturan Toko</h4>
        <small class="text-muted">Kelola informasi toko, kebijakan sewa, dan pengaturan sistem.</small>
    </div>
</div>

<form method="POST" action="{{ route('admin.pengaturan.update') }}" class="needs-validation">
    @csrf
    @method('PUT')

    <div class="row g-4">
        <!-- Kolom Kiri: Informasi Dasar -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
                <h6 class="fw-bold mb-3 border-bottom pb-2">Informasi Dasar Toko</h6>
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Nama Toko</label>
                    <input type="text" name="nama_toko" class="form-control form-control-sm @error('nama_toko') is-invalid @enderror" 
                           value="{{ $setting->nama_toko ?? 'HIKETRIP RENTAL' }}" required>
                    @error('nama_toko')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Tagline</label>
                    <input type="text" name="tagline" class="form-control form-control-sm" 
                           value="{{ $setting->tagline ?? '' }}" placeholder="Slogan toko Anda">
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Alamat</label>
                    <textarea name="alamat" class="form-control form-control-sm" rows="3">{{ $setting->alamat ?? '' }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Telepon</label>
                    <input type="text" name="telepon" class="form-control form-control-sm" 
                           value="{{ $setting->telepon ?? '' }}" placeholder="(0xxx) xxx-xxxx">
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">WhatsApp</label>
                    <input type="text" name="whatsapp" class="form-control form-control-sm" 
                           value="{{ $setting->whatsapp ?? '' }}" placeholder="62xxx-xxxx-xxxx">
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Email</label>
                    <input type="email" name="email" class="form-control form-control-sm" 
                           value="{{ $setting->email ?? '' }}" placeholder="admin@toko.com">
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Jam Operasional</label>
                    <input type="text" name="jam_operasional" class="form-control form-control-sm" 
                           value="{{ $setting->jam_operasional ?? '' }}" placeholder="Senin-Minggu, 08:00-17:00">
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Pengaturan Sewa -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
                <h6 class="fw-bold mb-3 border-bottom pb-2">Kebijakan Sewa</h6>
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Biaya Pengiriman (Flat)</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text">Rp</span>
                        <input type="number" name="biaya_pengiriman_flat" class="form-control @error('biaya_pengiriman_flat') is-invalid @enderror" 
                               value="{{ $setting->biaya_pengiriman_flat ?? '0' }}" required>
                    </div>
                    @error('biaya_pengiriman_flat')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Denda Keterlambatan (Per Hari)</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text">Rp</span>
                        <input type="number" name="denda_keterlambatan_per_hari" class="form-control @error('denda_keterlambatan_per_hari') is-invalid @enderror" 
                               value="{{ $setting->denda_keterlambatan_per_hari ?? '50000' }}" required>
                    </div>
                    <small class="text-muted d-block mt-1">Denda yang dikenakan untuk setiap hari keterlambatan pengembalian.</small>
                    @error('denda_keterlambatan_per_hari')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Syarat & Ketentuan</label>
                    <textarea name="syarat_ketentuan" class="form-control form-control-sm" rows="6">{{ $setting->syarat_ketentuan ?? '' }}</textarea>
                    <small class="text-muted d-block mt-1">Syarat umum yang harus disetujui pelanggan saat booking.</small>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-check-circle me-1"></i> Simpan Pengaturan
        </button>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-light border">Batal</a>
    </div>
</form>

@endsection
