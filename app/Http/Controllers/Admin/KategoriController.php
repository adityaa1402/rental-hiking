<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use App\Models\Pengaturan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class KategoriController extends Controller
{
    public function index()
    {
        $setting = Pengaturan::getSetting();
        $kategoriList = Kategori::withCount('alatHiking')->orderBy('id_kategori')->get();
        return view('admin.kategori.index', compact('setting', 'kategoriList'));
    }

    public function create()
    {
        $setting = Pengaturan::getSetting();
        return view('admin.kategori.create', compact('setting'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:100|unique:kategori,nama_kategori',
            'deskripsi'     => 'nullable|string',
            'icon'          => 'nullable|string|max:50',
        ]);

        Kategori::create([
            'nama_kategori' => $request->nama_kategori,
            'slug'          => Str::slug($request->nama_kategori),
            'deskripsi'     => $request->deskripsi,
            'icon'          => $request->icon ?? 'bi-compass',
        ]);

        return redirect()->route('admin.kategori.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function show(int $id)
    {
        $setting = Pengaturan::getSetting();
        $kategori = Kategori::with('alatHiking')->findOrFail($id);
        return view('admin.kategori.show', compact('setting', 'kategori'));
    }

    public function edit(int $id)
    {
        $setting = Pengaturan::getSetting();
        $kategori = Kategori::findOrFail($id);
        return view('admin.kategori.edit', compact('setting', 'kategori'));
    }

    public function update(Request $request, int $id)
    {
        $kategori = Kategori::findOrFail($id);
        $request->validate([
            'nama_kategori' => 'required|string|max:100|unique:kategori,nama_kategori,' . $id . ',id_kategori',
            'deskripsi'     => 'nullable|string',
            'icon'          => 'nullable|string|max:50',
        ]);

        $kategori->update([
            'nama_kategori' => $request->nama_kategori,
            'slug'          => Str::slug($request->nama_kategori),
            'deskripsi'     => $request->deskripsi,
            'icon'          => $request->icon ?? $kategori->icon,
        ]);

        return redirect()->route('admin.kategori.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(int $id)
    {
        $kategori = Kategori::findOrFail($id);
        if ($kategori->alatHiking()->count() > 0) {
            return back()->with('error', 'Tidak bisa menghapus kategori yang masih memiliki barang.');
        }
        $kategori->delete();
        return redirect()->route('admin.kategori.index')->with('success', 'Kategori berhasil dihapus.');
    }
}

