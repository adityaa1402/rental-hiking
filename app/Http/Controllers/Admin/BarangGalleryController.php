<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AlatHiking;
use App\Models\BarangGallery;
use App\Models\Pengaturan;
use Illuminate\Http\Request;

class BarangGalleryController extends Controller
{
    /**
     * Tampilkan form upload gambar untuk barang tertentu
     */
    public function create(int $id)
    {
        $setting = Pengaturan::getSetting();
        $barang = AlatHiking::with('kategori', 'gallery')->findOrFail($id);
        return view('admin.barang.gallery-create', compact('setting', 'barang'));
    }

    /**
     * Simpan gambar ke gallery
     */
    public function store(Request $request, int $id)
    {
        $barang = AlatHiking::findOrFail($id);

        $request->validate([
            'gambar.*'    => 'required|image|mimes:jpg,jpeg,png,webp|max:3072',
            'keterangan.*' => 'nullable|string|max:255',
        ]);

        $maxUrutan = BarangGallery::where('id_alat', $id)->max('urutan') ?? 0;
        $urutan = $maxUrutan + 1;

        if ($request->hasFile('gambar')) {
            foreach ($request->file('gambar') as $index => $file) {
                $gambarPath = $file->store('barang', 'public');
                BarangGallery::create([
                    'id_alat'     => $id,
                    'gambar'      => basename($gambarPath),
                    'keterangan'  => $request->input("keterangan.{$index}"),
                    'urutan'      => $urutan + $index,
                ]);
            }
        }

        return redirect()->route('admin.barang.gallery.create', $id)
            ->with('success', 'Gambar berhasil ditambahkan ke gallery.');
    }

    /**
     * Hapus gambar dari gallery
     */
    public function destroy(int $id, int $galleryId)
    {
        $barang = AlatHiking::findOrFail($id);
        $gallery = BarangGallery::findOrFail($galleryId);

        if ($gallery->id_alat != $id) {
            return redirect()->back()->with('error', 'Gambar tidak ditemukan.');
        }

        $gallery->delete();

        return redirect()->route('admin.barang.gallery.create', $id)
            ->with('success', 'Gambar berhasil dihapus.');
    }

    /**
     * Update urutan gambar
     */
    public function reorder(Request $request, int $id)
    {
        $request->validate([
            'urutan' => 'required|array',
            'urutan.*' => 'integer',
        ]);

        foreach ($request->input('urutan') as $galleryId => $urutan) {
            BarangGallery::where('id_gallery', $galleryId)
                ->where('id_alat', $id)
                ->update(['urutan' => $urutan]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Set gambar sebagai cover/thumbnail
     */
    public function setCover(int $id, int $galleryId)
    {
        $barang = AlatHiking::findOrFail($id);
        $gallery = BarangGallery::findOrFail($galleryId);

        if ($gallery->id_alat != $id) {
            return redirect()->back()->with('error', 'Gambar tidak ditemukan.');
        }

        // Reset urutan semua gambar, set cover ke urutan 0
        BarangGallery::where('id_alat', $id)->update(['urutan' => 99]);
        $gallery->update(['urutan' => 0]);

        return redirect()->route('admin.barang.gallery.create', $id)
            ->with('success', 'Gambar berhasil dijadikan cover.');
    }
}
