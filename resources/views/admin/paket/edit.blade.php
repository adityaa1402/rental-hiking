@extends('layouts.admin')

@section('title', 'Edit Paket')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Edit Paket: {{ $paket->nama_paket }}</h4>
        <small class="text-muted">Perbarui informasi dan item paket.</small>
    </div>
</div>

<form method="POST" action="{{ route('admin.paket.update', $paket->id_paket) }}" enctype="multipart/form-data" class="needs-validation">
    @csrf
    @method('PUT')

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
                           value="{{ old('nama_paket', $paket->nama_paket) }}" required>
                    @error('nama_paket')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Deskripsi</label>
                    <textarea name="deskripsi" class="form-control form-control-sm @error('deskripsi') is-invalid @enderror" 
                              rows="4" required>{{ old('deskripsi', $paket->deskripsi) }}</textarea>
                    @error('deskripsi')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Kapasitas Orang</label>
                        <input type="number" name="kapasitas_orang" class="form-control form-control-sm @error('kapasitas_orang') is-invalid @enderror"
                               value="{{ old('kapasitas_orang', $paket->kapasitas_orang) }}" min="1" required>
                        @error('kapasitas_orang')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Harga Paket/Hari</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text">Rp</span>
                            <input type="number" name="harga_paket_per_hari" class="form-control @error('harga_paket_per_hari') is-invalid @enderror"
                                   value="{{ old('harga_paket_per_hari', $paket->harga_paket_per_hari) }}" min="0" required>
                        </div>
                        @error('harga_paket_per_hari')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
                <h6 class="fw-bold mb-3 border-bottom pb-2">Gambar Paket</h6>
                @if($paket->gambar)
                    <div class="mb-3">
                        <img src="{{ getPackageImageUrl($paket) }}" alt="{{ $paket->nama_paket }}" class="img-fluid rounded" style="max-height:200px;">
                    </div>
                @endif
                <div class="mb-3">
                    <input type="file" name="gambar" class="form-control form-control-sm @error('gambar') is-invalid @enderror" accept="image/*">
                    @error('gambar')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    <small class="text-muted d-block mt-1">Kosongkan jika tidak ingin mengganti</small>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                <h6 class="fw-bold mb-3 border-bottom pb-2">Status</h6>
                <div class="form-check">
                    <input type="checkbox" name="status_aktif" class="form-check-input" id="statusAktif" value="1" {{ old('status_aktif', $paket->status_aktif) ? 'checked' : '' }}>
                    <label class="form-check-label" for="statusAktif">
                        Aktifkan paket
                    </label>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
        <h6 class="fw-bold mb-3 border-bottom pb-2">Item Paket</h6>

        <div id="itemsContainer">
            @forelse(old('items', $paket->detailPaket->map(fn ($detail) => [
                'id_alat' => $detail->id_alat,
                'jumlah' => $detail->jumlah_unit,
            ])->all()) as $idx => $detail)
                <div class="item-row mb-3">
                    <div class="row g-2">
                        <div class="col-md-9">
                            <select name="items[{{ $idx }}][id_alat]" class="form-select form-select-sm" required>
                                <option value="">-- Pilih Barang --</option>
                                @foreach($alatList as $alat)
                                    <option value="{{ $alat->id_alat }}" {{ $detail['id_alat'] == $alat->id_alat ? 'selected' : '' }}>
                                        {{ $alat->nama_alat }} - {{ $alat->merk }} ({{ formatRupiah($alat->harga_per_hari) }}/hari)
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input type="number" name="items[{{ $idx }}][jumlah]" class="form-control form-control-sm"
                                   placeholder="Qty" min="1" value="{{ $detail['jumlah'] }}" required>
                        </div>
                        <div class="col-md-1">
                            <button type="button" class="btn btn-outline-danger btn-sm w-100" onclick="removeItem(this)">Hapus</button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="item-row mb-3">
                    <div class="row g-2">
                        <div class="col-md-9">
                            <select name="items[0][id_alat]" class="form-select form-select-sm" required>
                                <option value="">-- Pilih Barang --</option>
                                @foreach($alatList as $alat)
                                    <option value="{{ $alat->id_alat }}">{{ $alat->nama_alat }} - {{ $alat->merk }} ({{ formatRupiah($alat->harga_per_hari) }}/hari)</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input type="number" name="items[0][jumlah]" class="form-control form-control-sm" placeholder="Qty" min="1" value="1" required>
                        </div>
                        <div class="col-md-1">
                            <button type="button" class="btn btn-outline-danger btn-sm w-100" onclick="removeItem(this)">Hapus</button>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>

        <button type="button" class="btn btn-outline-primary btn-sm align-self-start" onclick="addItem()">
            <i class="bi bi-plus-circle me-1"></i> Tambah Item
        </button>
    </div>

    <div class="d-flex gap-2 mt-4">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-check-circle me-1"></i> Perbarui
        </button>
        <a href="{{ route('admin.paket.index') }}" class="btn btn-light border">Batal</a>
    </div>
</form>

<script>
let itemCount = {{ max($paket->detailPaket->count(), count(old('items', []))) }};

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
