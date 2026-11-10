@extends('layouts.admin')

@section('title', 'Edit Barang')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1" style="color:#173F35;">Edit Barang</h4>
        <small class="text-muted">Perbarui informasi perlengkapan hiking.</small>
    </div>
</div>

<form method="POST" action="{{ route('admin.barang.update', $barang->id_alat) }}" enctype="multipart/form-data" class="needs-validation">
    @csrf
    @method('PUT')

    <div class="row g-4">
        <div class="col-lg-8">
            <!-- Informasi Umum -->
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
                <h6 class="fw-bold mb-3 border-bottom pb-2" style="color:#173F35;">Informasi Umum</h6>

                <div class="mb-3">
                    <label class="form-label fw-bold" style="color:#173F35;">Kategori</label>
                    <select name="id_kategori" class="form-select form-select-sm @error('id_kategori') is-invalid @enderror" required>
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($kategoriList as $kat)
                            <option value="{{ $kat->id_kategori }}" {{ $barang->id_kategori == $kat->id_kategori ? 'selected' : '' }}>
                                {{ $kat->nama_kategori }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_kategori')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold" style="color:#173F35;">Nama Barang</label>
                    <input type="text" name="nama_alat" class="form-control form-control-sm @error('nama_alat') is-invalid @enderror" 
                           placeholder="Contoh: Tenda Dome 2 Orang" value="{{ $barang->nama_alat }}" required>
                    @error('nama_alat')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold" style="color:#173F35;">Merek</label>
                    <input type="text" name="merk" class="form-control form-control-sm @error('merk') is-invalid @enderror" 
                           placeholder="Contoh: Decathlon, Coleman, Mountain Hardwear" value="{{ $barang->merk }}" required>
                    @error('merk')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold" style="color:#173F35;">Deskripsi</label>
                    <textarea name="deskripsi" class="form-control form-control-sm @error('deskripsi') is-invalid @enderror" 
                              rows="4" placeholder="Jelaskan fitur, keunggulan, dan karakteristik barang..." required>{{ $barang->deskripsi }}</textarea>
                    @error('deskripsi')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold" style="color:#173F35;">Spesifikasi</label>
                    <textarea name="spesifikasi" class="form-control form-control-sm @error('spesifikasi') is-invalid @enderror" 
                              rows="3" placeholder="Contoh: Berat: 2.5kg, Material: Nylon, Kapasitas: 2 orang..." required>{{ $barang->spesifikasi }}</textarea>
                    @error('spesifikasi')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Harga & Stok -->
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
                <h6 class="fw-bold mb-3 border-bottom pb-2" style="color:#173F35;">Harga & Stok</h6>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold" style="color:#173F35;">Harga per Hari</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text">Rp</span>
                            <input type="number" name="harga_per_hari" class="form-control @error('harga_per_hari') is-invalid @enderror" 
                                   min="0" placeholder="0" value="{{ $barang->harga_per_hari }}" required>
                        </div>
                        @error('harga_per_hari')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold" style="color:#173F35;">Stok Total</label>
                        <input type="number" name="stok_total" class="form-control form-control-sm @error('stok_total') is-invalid @enderror" 
                               min="0" placeholder="0" value="{{ $barang->stok_total }}" required>
                        @error('stok_total')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold" style="color:#173F35;">Unit Rusak / Tidak Siap Sewa</label>
                        <input type="number" name="stok_rusak" class="form-control form-control-sm @error('stok_rusak') is-invalid @enderror"
                               min="0" max="{{ $barang->stok_total }}" value="{{ $barang->stok_rusak }}" required>
                        @error('stok_rusak')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Kurangi angka ini jika barang sudah diperbaiki dan siap disewakan lagi.</small>
                    </div>
                </div>
            </div>

            <!-- Kondisi -->
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                <h6 class="fw-bold mb-3 border-bottom pb-2" style="color:#173F35;">Kondisi Barang</h6>

                <div class="mb-0">
                    <label class="form-label fw-bold" style="color:#173F35;">Status Kondisi</label>
                    <select name="kondisi" class="form-select form-select-sm @error('kondisi') is-invalid @enderror" required>
                        <option value="">-- Pilih Kondisi --</option>
                        <option value="Baik" {{ $barang->kondisi == 'Baik' ? 'selected' : '' }}>Baik</option>
                        <option value="Perlu Perawatan" {{ $barang->kondisi == 'Perlu Perawatan' ? 'selected' : '' }}>Perlu Perawatan</option>
                        <option value="Rusak" {{ $barang->kondisi == 'Rusak' ? 'selected' : '' }}>Rusak</option>
                        <option value="Sedang Dibersihkan" {{ $barang->kondisi == 'Sedang Dibersihkan' ? 'selected' : '' }}>Sedang Dibersihkan</option>
                    </select>
                    @error('kondisi')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Sidebar: Gambar -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white sticky-panel">
                <h6 class="fw-bold mb-3 border-bottom pb-2" style="color:#173F35;">Foto Barang</h6>
                
                <div class="mb-3">
                    <div class="position-relative">
                        <input type="file" name="gambar" id="gambarInput" class="form-control form-control-sm @error('gambar') is-invalid @enderror" 
                               accept="image/*">
                        @error('gambar')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <small class="text-muted d-block mt-2">
                        <i class="bi bi-info-circle me-1"></i>Format: JPG, PNG, WebP (max 3MB). Kosongkan jika tidak ingin mengubah.
                    </small>
                </div>

                <!-- Preview -->
                <div class="mb-3">
                    <div id="imagePreview" class="rounded-3 bg-light d-flex align-items-center justify-content-center" 
                         style="width:100%;height:250px;min-height:250px;">
                        @if($barang->gambar)
                            <img src="{{ getProductImageUrl($barang) }}" alt="{{ $barang->nama_alat }}" style="width:100%;height:250px;object-fit:cover;" class="rounded-3">
                        @else
                            <div class="text-center">
                                <i class="bi bi-image text-muted" style="font-size:3rem;"></i>
                                <p class="text-muted small mt-2">Belum ada foto</p>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="alert alert-info small" style="background:#e8f5e9;border:1px solid #c8e6c9;">
                    <i class="bi bi-lightbulb me-2" style="color:#173F35;"></i>
                    <strong>Tips:</strong> Gunakan foto yang jelas dan dengan latar belakang sederhana untuk hasil terbaik.
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex gap-2 mt-4 mb-3">
        <button type="submit" class="btn btn-brand px-4">
            <i class="bi bi-check-circle me-1"></i> Simpan Perubahan
        </button>
        <a href="{{ route('admin.barang.index') }}" class="btn btn-light border">Batal</a>
    </div>
</form>

<script>
document.getElementById('gambarInput').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(event) {
            const preview = document.getElementById('imagePreview');
            preview.innerHTML = `<img src="${event.target.result}" style="width:100%;height:250px;object-fit:cover;" class="rounded-3">`;
        };
        reader.readAsDataURL(file);
    }
});
</script>

@endsection
