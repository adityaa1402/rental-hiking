@extends('layouts.admin')

@section('title', 'Data Barang')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0" style="color:#173F35;"><i class="bi bi-box-seam me-2"></i>Data Barang</h4>
        <div class="text-muted small">Total: {{ $alats->total() }} barang</div>
    </div>
    <a href="{{ route('admin.barang.create') }}" class="btn btn-brand px-4">
        <i class="bi bi-plus-circle me-1"></i> Tambah Barang
    </a>
</div>

{{-- Filter --}}
<div class="card border-0 rounded-4 shadow-sm mb-4">
    <div class="card-body p-4">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label small fw-semibold text-muted">Cari Nama Barang</label>
                <input type="text" name="q" class="form-control" placeholder="Nama alat..." value="{{ request('q') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold text-muted">Kategori</label>
                <select name="kategori" class="form-select">
                    <option value="">Semua Kategori</option>
                    @foreach($kategoris as $kat)
                    <option value="{{ $kat->id_kategori }}" {{ request('kategori')==$kat->id_kategori?'selected':'' }}>
                        {{ $kat->nama_kategori }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold text-muted">Kondisi</label>
                <select name="kondisi" class="form-select">
                    <option value="">Semua Kondisi</option>
                    <option value="Baik Sekali" {{ request('kondisi')=='Baik Sekali'?'selected':'' }}>Baik Sekali</option>
                    <option value="Baik" {{ request('kondisi')=='Baik'?'selected':'' }}>Baik</option>
                    <option value="Cukup" {{ request('kondisi')=='Cukup'?'selected':'' }}>Cukup</option>
                    <option value="Perlu Perbaikan" {{ request('kondisi')=='Perlu Perbaikan'?'selected':'' }}>Perlu Perbaikan</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-brand w-100">
                    <i class="bi bi-search me-1"></i>Filter
                </button>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 rounded-4 shadow-sm">
    <div class="card-body p-0">
        @if($alats->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead style="background:#f8faf9;">
                    <tr class="small text-muted">
                        <th class="ps-4 py-3">Foto</th>
                        <th>Nama Barang</th>
                        <th>Kategori</th>
                        <th>Harga/Hari</th>
                        <th class="text-center">Stok</th>
                        <th class="text-center">Kondisi</th>
                        <th class="text-center pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($alats as $alat)
                    <tr>
                        <td class="ps-4">
                            @if($alat->gambar)
                                <img src="{{ Storage::url($alat->gambar) }}" class="rounded-3"
                                    style="width:55px;height:50px;object-fit:cover;">
                            @else
                                <div class="rounded-3 bg-light d-flex align-items-center justify-content-center"
                                    style="width:55px;height:50px;">
                                    <i class="bi bi-image text-muted"></i>
                                </div>
                            @endif
                        </td>
                        <td>
                            <div class="fw-semibold" style="color:#173F35;">{{ $alat->nama_alat }}</div>
                            @if($alat->merk)
                            <div class="text-muted small">{{ $alat->merk }}</div>
                            @endif
                        </td>
                        <td class="small">
                            <span class="badge" style="background:#e8f5e9;color:#173F35;">
                                {{ $alat->kategori->nama_kategori ?? '-' }}
                            </span>
                        </td>
                        <td class="fw-semibold small" style="color:#173F35;">
                            Rp {{ number_format($alat->harga_per_hari, 0, ',', '.') }}
                        </td>
                        <td class="text-center">
                            <span class="badge rounded-pill {{ $alat->stok_total > 0 ? 'bg-success' : 'bg-danger' }}">
                                {{ $alat->stok_total }}
                            </span>
                        </td>
                        <td class="text-center">
                            <span class="badge
                                @if($alat->kondisi=='Baik Sekali') bg-primary
                                @elseif($alat->kondisi=='Baik') bg-success
                                @elseif($alat->kondisi=='Cukup') bg-warning text-dark
                                @else bg-danger @endif
                                " style="font-size:0.72rem;">
                                {{ $alat->kondisi }}
                            </span>
                        </td>
                        <td class="text-center pe-4">
                            <div class="d-flex gap-1 justify-content-center">
                                <a href="{{ route('admin.barang.show', $alat->id_alat) }}"
                                    class="btn btn-sm btn-outline-secondary" title="Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('admin.barang.edit', $alat->id_alat) }}"
                                    class="btn btn-sm btn-outline-warning" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('admin.barang.destroy', $alat->id_alat) }}" method="POST"
                                    onsubmit="return confirm('Hapus barang {{ $alat->nama_alat }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-4">
            {{ $alats->appends(request()->query())->links() }}
        </div>
        @else
        <div class="text-center py-5 text-muted">
            <i class="bi bi-box-seam mb-3 d-block" style="font-size:3.5rem;"></i>
            <h5 class="fw-bold">Belum ada data barang</h5>
            <p class="small">Tambahkan barang pertama untuk memulai.</p>
            <a href="{{ route('admin.barang.create') }}" class="btn btn-brand">
                <i class="bi bi-plus-circle me-1"></i> Tambah Barang
            </a>
        </div>
        @endif
    </div>
</div>

@endsection

