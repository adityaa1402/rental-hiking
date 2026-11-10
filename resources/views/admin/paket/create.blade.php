@extends('layouts.admin')

@section('title', 'Tambah Paket')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Tambah Paket Sewa Baru</h4>
        <small class="text-muted">Buat paket bundling perlengkapan untuk pelanggan.</small>
    </div>
</div>

<form method="POST" action="{{ route('admin.paket.store') }}" enctype="multipart/form-data" class="needs-validation">
    @csrf

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
                <h6 class="fw-bold mb-3 border-bottom pb-2">Informasi Paket</h6>

                <div class="mb-3">
                    <label class="form-label fw-bold">Nama Paket</label>
                    <input type="text" name="nama_paket" class="form-control form-control-sm @error('nama_paket') is-invalid @enderror" 
                           placeholder="Contoh: Paket Camping 2 Hari, Paket Mountain Trek" value="{{ old('nama_paket') }}" required>
                    @error('nama_paket')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Deskripsi</label>
                    <textarea name="deskripsi" class="form-control form-control-sm @error('deskripsi') is-invalid @enderror" 
                              rows="4" placeholder="Jelaskan paket ini..." required>{{ old('deskripsi') }}</textarea>
                    @error('deskripsi')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Kapasitas Orang</label>
                        <input type="number" name="kapasitas_orang" class="form-control form-control-sm @error('kapasitas_orang') is-invalid @enderror" 
                               min="1" value="{{ old('kapasitas_orang', 1) }}" required>
                        @error('kapasitas_orang')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Harga Paket/Hari</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text">Rp</span>
                            <input type="number" name="harga_paket_per_hari" class="form-control @error('harga_paket_per_hari') is-invalid @enderror" 
                                   min="0" value="{{ old('harga_paket_per_hari') }}" required>
                        </div>
                        @error('harga_paket_per_hari')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

        </div>

        <!-- Sidebar: Gambar & Status -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
                <h6 class="fw-bold mb-3 border-bottom pb-2">Gambar Paket</h6>
                <div class="mb-3">
                    <input type="file" name="gambar" class="form-control form-control-sm @error('gambar') is-invalid @enderror" 
                           accept="image/*" required>
                    @error('gambar')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                    <small class="text-muted d-block mt-1">Format: JPG, PNG, WebP (max 3MB)</small>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                <h6 class="fw-bold mb-3 border-bottom pb-2">Status</h6>
                <div class="form-check">
                    <input type="checkbox" name="status_aktif" class="form-check-input" id="statusAktif" value="1" {{ old('status_aktif', '1') ? 'checked' : '' }}>
                    <label class="form-check-label" for="statusAktif">
                        Aktifkan paket
                    </label>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
        <h6 class="fw-bold mb-3 border-bottom pb-2">
            <i class="bi bi-list-check me-2"></i>Pilih Item Paket
        </h6>

        <div id="itemsContainer">
            <div class="item-row mb-3">
                <div class="row g-2">
                    <div class="col-md-9">
                        <select name="items[0][id_alat]" class="form-select form-select-sm" required>
                            <option value="">-- Pilih Barang --</option>
                            @foreach($alatList as $alat)
                                <option value="{{ $alat->id_alat }}" {{ old('items.0.id_alat') == $alat->id_alat ? 'selected' : '' }}>
                                    {{ $alat->nama_alat }} - {{ $alat->merk }} ({{ formatRupiah($alat->harga_per_hari) }}/hari)
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="number" name="items[0][jumlah]" class="form-control form-control-sm" placeholder="Qty" min="1" value="{{ old('items.0.jumlah', 1) }}" required>
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-outline-danger btn-sm w-100" onclick="removeItem(this)">Hapus</button>
                    </div>
                </div>
            </div>
        </div>

        <button type="button" class="btn btn-outline-primary btn-sm align-self-start" onclick="addItem()">
            <i class="bi bi-plus-circle me-1"></i> Tambah Item
        </button>
    </div>

    <div class="d-flex gap-2 mt-4">
        <button type="submit" class="btn btn-success">
            <i class="bi bi-check-circle me-1"></i> Simpan Paket
        </button>
        <a href="{{ route('admin.paket.index') }}" class="btn btn-light border">Batal</a>
    </div>
</form>

<script>
let itemCount = 1;

function addItem() {
    const container = document.getElementById('itemsContainer');
    const row = document.createElement('div');
    row.className = 'item-row mb-3';
    row.innerHTML = `
        <div class="row g-2">
            <div class="col-md-9">
                <select name="items[${itemCount}][id_alat]" class="form-select form-select-sm" required>
                    <option value="">-- Pilih Barang --</option>
                    @foreach($alatList as $alat)
                        <option value="{{ $alat->id_alat }}">{{ $alat->nama_alat }} - {{ $alat->merk }} ({{ formatRupiah($alat->harga_per_hari) }}/hari)</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <input type="number" name="items[${itemCount}][jumlah]" class="form-control form-control-sm" placeholder="Qty" min="1" value="1">
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-outline-danger btn-sm w-100" onclick="removeItem(this)">Hapus</button>
            </div>
        </div>
    `;
    container.appendChild(row);
    itemCount++;
}

function removeItem(btn) {
    btn.closest('.item-row').remove();
}
</script>

@endsection
