<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AlatHiking;
use App\Models\Kategori;
use App\Models\Pengaturan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BarangController extends Controller
{
    public function index(Request $request)
    {
        $setting = Pengaturan::getSetting();
        $query = AlatHiking::with('kategori');

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(fn($sq) => $sq->where('nama_alat', 'like', "%{$q}%")->orWhere('merk', 'like', "%{$q}%"));
        }
        if ($request->filled('kategori')) {
            $query->where('id_kategori', $request->kategori);
        }
        if ($request->filled('kondisi')) {
            $query->where('kondisi', $request->kondisi);
        }

        $barangList = $query->orderBy('id_alat', 'desc')->paginate(15)->withQueryString();
        $kategoriList = Kategori::all();

        return view('admin.barang.index', compact('setting', 'barangList', 'kategoriList'));
    }

    public function create()
    {
        $setting = Pengaturan::getSetting();
        $kategoriList = Kategori::all();
        return view('admin.barang.create', compact('setting', 'kategoriList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_kategori'   => 'required|exists:kategori,id_kategori',
            'nama_alat'     => 'required|string|max:150',
            'merk'          => 'required|string|max:100',
            'deskripsi'     => 'required|string',
            'spesifikasi'   => 'required|string',
            'harga_per_hari'=> 'required|numeric|min:0',
            'stok_total'    => 'required|integer|min:0',
            'stok_rusak'    => 'nullable|integer|min:0|lte:stok_total',
            'kondisi'       => 'required|in:Baik,Perlu Perawatan,Rusak,Sedang Dibersihkan',
            'gambar'        => 'required|image|mimes:jpg,jpeg,png,webp|max:3072',
        ]);

        $gambarPath = $request->file('gambar')->store('barang', 'public');
        $slug = Str::slug($request->nama_alat) . '-' . time();

        AlatHiking::create(array_merge($request->except('gambar'), [
            'slug'   => $slug,
            'gambar' => basename($gambarPath),
        ]));

        return redirect()->route('admin.barang.index')->with('success', 'Barang berhasil ditambahkan.');
    }

    public function show(int $id)
    {
        $setting = Pengaturan::getSetting();
        $barang = AlatHiking::with('kategori')->findOrFail($id);
        return view('admin.barang.show', compact('setting', 'barang'));
    }

    public function edit(int $id)
    {
        $setting = Pengaturan::getSetting();
        $barang = AlatHiking::findOrFail($id);
        $kategoriList = Kategori::all();
        return view('admin.barang.edit', compact('setting', 'barang', 'kategoriList'));
    }

    public function update(Request $request, int $id)
    {
        $barang = AlatHiking::findOrFail($id);
        $request->validate([
            'id_kategori'   => 'required|exists:kategori,id_kategori',
            'nama_alat'     => 'required|string|max:150',
            'merk'          => 'required|string|max:100',
            'deskripsi'     => 'required|string',
            'spesifikasi'   => 'required|string',
            'harga_per_hari'=> 'required|numeric|min:0',
            'stok_total'    => 'required|integer|min:0',
            'stok_rusak'    => 'required|integer|min:0|lte:stok_total',
            'kondisi'       => 'required|in:Baik,Perlu Perawatan,Rusak,Sedang Dibersihkan',
            'gambar'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3072',
        ]);

        $data = $request->except('gambar');
        if ($request->hasFile('gambar')) {
            $gambarPath = $request->file('gambar')->store('barang', 'public');
            $data['gambar'] = basename($gambarPath);
        }

        $barang->update($data);
        return redirect()->route('admin.barang.index')->with('success', 'Barang berhasil diperbarui.');
    }

    public function destroy(int $id)
    {
        $barang = AlatHiking::findOrFail($id);
        $barang->delete();
        return redirect()->route('admin.barang.index')->with('success', 'Barang berhasil dihapus.');
    }
}
