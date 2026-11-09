@extends('layouts.admin')

@section('title', 'Kelola Gallery Barang')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1" style="color:#173F35;">Gallery Barang</h4>
        <small class="text-muted">Kelola gambar untuk: <strong>{{ $barang->nama_alat }}</strong> ({{ $barang->merk }})</small>
    </div>
    <a href="{{ route('admin.barang.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Kembali
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-circle me-2"></i>
        <strong>Ada kesalahan:</strong>
        <ul class="mb-0 mt-2">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row g-4">
    <!-- Form Upload -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
            <h6 class="fw-bold mb-3 border-bottom pb-2" style="color:#173F35;">
                <i class="bi bi-cloud-upload me-2"></i>Upload Gambar
            </h6>

            <form method="POST" action="{{ route('admin.barang.gallery.store', $barang->id_alat) }}" enctype="multipart/form-data">
                @csrf

                <div class="mb-4">
                    <label class="form-label fw-bold" style="color:#173F35;">
                        Pilih Gambar <span class="text-danger">*</span>
                    </label>
                    <div class="position-relative">
                        <div class="upload-zone border-2 border-dashed rounded-3 p-4 text-center cursor-pointer transition"
                             id="uploadZone"
                             style="border-color: #173F35; background-color: #f8f9fa; min-height: 200px; display: flex; align-items: center; justify-content: center;">
                            <input type="file" name="gambar[]" id="gambarInput" class="d-none" multiple accept="image/*" required>
                            <div class="text-center">
                                <i class="bi bi-image fs-1 text-muted mb-2" style="display: block;"></i>
                                <p class="fw-semibold mb-1" style="color:#173F35;">Drag & Drop atau Klik untuk Upload</p>
                                <small class="text-muted">Format: JPG, PNG, WEBP • Ukuran maksimal: 3MB per gambar</small>
                                <div class="mt-3">
                                    <span class="badge bg-info text-dark">Bisa upload multiple gambar sekaligus</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Preview gambar -->
                    <div id="previewContainer" class="mt-3"></div>
                </div>

                <div id="keteranganContainer" style="display: none;">
                    <label class="form-label fw-bold" style="color:#173F35;">Keterangan Gambar</label>
                    <div id="keteranganList" class="mb-3"></div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-brand" style="background-color: #173F35;">
                        <i class="bi bi-upload me-1"></i> Upload Gambar
                    </button>
                    <button type="reset" class="btn btn-outline-secondary">Reset</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Info Barang -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
            <h6 class="fw-bold mb-3 border-bottom pb-2" style="color:#173F35;">
                <i class="bi bi-info-circle me-2"></i>Informasi Barang
            </h6>

            <div class="mb-3">
                <small class="text-muted d-block mb-1">Kategori</small>
                <p class="mb-0 fw-semibold">{{ $barang->kategori->nama_kategori }}</p>
            </div>

            <div class="mb-3">
                <small class="text-muted d-block mb-1">Merek</small>
                <p class="mb-0 fw-semibold">{{ $barang->merk }}</p>
            </div>

            <div class="mb-3">
                <small class="text-muted d-block mb-1">Harga Sewa</small>
                <p class="mb-0 fw-semibold">{{ formatRupiah($barang->harga_per_hari) }}/hari</p>
            </div>

            <div class="mb-3">
                <small class="text-muted d-block mb-1">Stok Total</small>
                <p class="mb-0 fw-semibold">{{ $barang->stok_total }} unit</p>
            </div>

            <div>
                <small class="text-muted d-block mb-1">Kondisi</small>
                <span class="badge" style="background-color: #173F35;">{{ $barang->kondisi }}</span>
            </div>
        </div>

        <!-- Statistik -->
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-light">
            <h6 class="fw-bold mb-3" style="color:#173F35;">
                <i class="bi bi-image me-2"></i>Statistik Gallery
            </h6>
            <div class="text-center">
                <p class="mb-0">
                    <strong style="font-size: 1.5rem;" class="text-brand" style="color: #173F35;">{{ $barang->gallery->count() }}</strong>
                    <br>
                    <small class="text-muted">Gambar Tersimpan</small>
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Gallery List -->
@if($barang->gallery->count() > 0)
    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mt-4">
        <h6 class="fw-bold mb-3 border-bottom pb-2" style="color:#173F35;">
            <i class="bi bi-images me-2"></i>Gambar yang Tersimpan ({{ $barang->gallery->count() }})
        </h6>

        <div class="row g-3" id="galleryList">
            @foreach($barang->gallery as $photo)
                @php
                    $imgSrc = asset('storage/barang/' . $photo->gambar);
                @endphp
                <div class="col-md-6 col-lg-3 gallery-item" data-gallery-id="{{ $photo->id_gallery }}">
                    <div class="card border-0 overflow-hidden h-100 shadow-sm gallery-card">
                        <!-- Gambar -->
                        <div class="position-relative" style="padding-top: 100%; overflow: hidden;">
                            <img src="{{ $imgSrc }}" 
                                 alt="Gallery {{ $photo->id_gallery }}" 
                                 class="position-absolute top-0 start-0 w-100 h-100 object-fit-cover"
                                 style="object-fit: cover;">

                            <!-- Badge Cover -->
                            @if($photo->urutan == 0)
                                <div class="position-absolute top-0 start-0 w-100 h-100" 
                                     style="background: rgba(0,0,0,0.3); display: flex; align-items: flex-start; justify-content: flex-end; padding: 0.5rem;">
                                    <span class="badge bg-success">
                                        <i class="bi bi-star-fill me-1"></i>Cover
                                    </span>
                                </div>
                            @endif
                        </div>

                        <!-- Card Body -->
                        <div class="card-body p-3">
                            @if($photo->keterangan)
                                <p class="card-text small text-muted mb-2">{{ $photo->keterangan }}</p>
                            @endif

                            <!-- Aksi Buttons -->
                            <div class="btn-group w-100" role="group">
                                @if($photo->urutan != 0)
                                    <form method="POST" action="{{ route('admin.barang.gallery.setCover', [$barang->id_alat, $photo->id_gallery]) }}" class="w-50">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-warning w-100" title="Jadikan Cover">
                                            <i class="bi bi-star"></i>
                                        </button>
                                    </form>
                                @else
                                    <button class="btn btn-sm btn-warning w-50 disabled" title="Ini adalah cover">
                                        <i class="bi bi-star-fill"></i>
                                    </button>
                                @endif

                                <button type="button" class="btn btn-sm btn-outline-danger w-50" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#deleteModal-{{ $photo->id_gallery }}"
                                        title="Hapus gambar">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Delete Modal -->
                <div class="modal fade" id="deleteModal-{{ $photo->id_gallery }}" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content border-0">
                            <div class="modal-header border-bottom-0 pb-0">
                                <h5 class="modal-title fw-bold" style="color:#173F35;">Hapus Gambar?</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <p class="text-muted mb-0">Apakah Anda yakin ingin menghapus gambar ini? Tindakan ini tidak dapat dibatalkan.</p>
                            </div>
                            <div class="modal-footer border-top-0 pt-0">
                                <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                                <form method="POST" action="{{ route('admin.barang.gallery.destroy', [$barang->id_alat, $photo->id_gallery]) }}" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@else
    <div class="alert alert-info alert-dismissible fade show mt-4" role="alert">
        <i class="bi bi-info-circle me-2"></i>
        <strong>Belum ada gambar.</strong> Mulai upload gambar untuk barang ini.
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<style>
    .upload-zone {
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .upload-zone:hover {
        background-color: #e8f5e9 !important;
        border-color: #173F35 !important;
    }

    .upload-zone.drag-over {
        background-color: #c8e6c9 !important;
        border-color: #173F35 !important;
    }

    .gallery-card:hover {
        box-shadow: 0 0.5rem 1rem rgba(23, 63, 53, 0.2) !important;
    }

    .object-fit-cover {
        object-fit: cover;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const uploadZone = document.getElementById('uploadZone');
    const gambarInput = document.getElementById('gambarInput');
    const previewContainer = document.getElementById('previewContainer');
    const keteranganContainer = document.getElementById('keteranganContainer');
    const keteranganList = document.getElementById('keteranganList');

    // Click to upload
    uploadZone.addEventListener('click', () => gambarInput.click());

    // Drag & Drop
    uploadZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        uploadZone.classList.add('drag-over');
    });

    uploadZone.addEventListener('dragleave', () => {
        uploadZone.classList.remove('drag-over');
    });

    uploadZone.addEventListener('drop', (e) => {
        e.preventDefault();
        uploadZone.classList.remove('drag-over');
        gambarInput.files = e.dataTransfer.files;
        handleFileSelect();
    });

    gambarInput.addEventListener('change', handleFileSelect);

    function handleFileSelect() {
        const files = Array.from(gambarInput.files);
        previewContainer.innerHTML = '';
        keteranganList.innerHTML = '';

        if (files.length === 0) {
            keteranganContainer.style.display = 'none';
            return;
        }

        keteranganContainer.style.display = 'block';

        files.forEach((file, index) => {
            // Preview
            const reader = new FileReader();
            reader.onload = (e) => {
                const preview = document.createElement('div');
                preview.className = 'col-md-3 mb-3';
                preview.innerHTML = `
                    <div class="card border-0 overflow-hidden">
                        <div style="padding-top: 100%; overflow: hidden; position: relative;">
                            <img src="${e.target.result}" 
                                 alt="Preview" 
                                 style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover;">
                        </div>
                        <div class="card-body p-2 text-center small text-muted">
                            ${file.name}
                        </div>
                    </div>
                `;
                previewContainer.appendChild(preview);
            };
            reader.readAsDataURL(file);

            // Keterangan input
            const keterangan = document.createElement('div');
            keterangan.className = 'mb-2';
            keterangan.innerHTML = `
                <label class="form-label small">Keterangan untuk: <strong>${file.name}</strong></label>
                <input type="text" name="keterangan[${index}]" class="form-control form-control-sm" 
                       placeholder="Contoh: Tampak depan, Detail strap, dll">
            `;
            keteranganList.appendChild(keterangan);
        });

        // Wrap preview
        if (files.length > 0) {
            const wrapper = document.createElement('div');
            wrapper.className = 'row';
            wrapper.id = 'previewWrapper';
            const items = previewContainer.querySelectorAll('.col-md-3');
            items.forEach(item => wrapper.appendChild(item));
            previewContainer.innerHTML = '';
            previewContainer.appendChild(wrapper);
        }
    }
});
</script>
@endsection
