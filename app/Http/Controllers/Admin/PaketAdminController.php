<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AlatHiking;
use App\Models\DetailPaket;
use App\Models\PaketSewa;
use App\Models\Pengaturan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PaketAdminController extends Controller
{
    public function index()
    {
        $setting = Pengaturan::getSetting();
        $paketList = PaketSewa::with('detailPaket')->orderByDesc('id_paket')->paginate(15);
        return view('admin.paket.index', compact('setting', 'paketList'));
    }

    public function create()
    {
        $setting = Pengaturan::getSetting();
        $alatList = AlatHiking::with('kategori')->where('kondisi', 'Baik')->get();
        return view('admin.paket.create', compact('setting', 'alatList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_paket'            => 'required|string|max:150',
            'deskripsi'             => 'required|string',
            'kapasitas_orang'       => 'required|integer|min:1',
            'harga_paket_per_hari'  => 'required|numeric|min:0',
            'gambar'                => 'required|image|mimes:jpg,jpeg,png,webp|max:3072',
            'items'                 => 'required|array|min:1',
        ]);

        $gambarPath = $request->file('gambar')->store('paket', 'public');

        $paket = PaketSewa::create([
            'nama_paket'           => $request->nama_paket,
            'slug'                 => Str::slug($request->nama_paket) . '-' . time(),
            'deskripsi'            => $request->deskripsi,
            'kapasitas_orang'      => $request->kapasitas_orang,
            'harga_paket_per_hari' => $request->harga_paket_per_hari,
            'gambar'               => basename($gambarPath),
            'status_aktif'         => $request->boolean('status_aktif', true) ? 1 : 0,
        ]);

        foreach ($request->items as $item) {
            if (!empty($item['id_alat']) && !empty($item['jumlah'])) {
                DetailPaket::create([
                    'id_paket'    => $paket->id_paket,
                    'id_alat'     => $item['id_alat'],
                    'jumlah_unit' => $item['jumlah'],
                ]);
            }
        }

        return redirect()->route('admin.paket.index')->with('success', 'Paket sewa berhasil ditambahkan.');
    }

    public function show(int $id)
    {
        $setting = Pengaturan::getSetting();
        $paket = PaketSewa::with('detailPaket.alatHiking')->findOrFail($id);
        return view('admin.paket.show', compact('setting', 'paket'));
    }

    public function edit(int $id)
    {
        $setting = Pengaturan::getSetting();
        $paket = PaketSewa::with('detailPaket')->findOrFail($id);
        $alatList = AlatHiking::with('kategori')->where('kondisi', 'Baik')->get();
        return view('admin.paket.edit', compact('setting', 'paket', 'alatList'));
    }

    public function update(Request $request, int $id)
    {
        $paket = PaketSewa::findOrFail($id);
        $request->validate([
            'nama_paket'            => 'required|string|max:150',
            'deskripsi'             => 'required|string',
            'kapasitas_orang'       => 'required|integer|min:1',
            'harga_paket_per_hari'  => 'required|numeric|min:0',
            'gambar'                => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3072',
        ]);

        $data = $request->except(['gambar', 'items', '_token', '_method']);
        $data['status_aktif'] = $request->boolean('status_aktif') ? 1 : 0;

        if ($request->hasFile('gambar')) {
            $gambarPath = $request->file('gambar')->store('paket', 'public');
            $data['gambar'] = basename($gambarPath);
        }

        $paket->update($data);

        // Update detail paket
        if ($request->has('items')) {
            $paket->detailPaket()->delete();
            foreach ($request->items as $item) {
                if (!empty($item['id_alat']) && !empty($item['jumlah'])) {
                    DetailPaket::create([
                        'id_paket'    => $paket->id_paket,
                        'id_alat'     => $item['id_alat'],
                        'jumlah_unit' => $item['jumlah'],
                    ]);
                }
            }
        }

        return redirect()->route('admin.paket.index')->with('success', 'Paket sewa berhasil diperbarui.');
    }

    public function destroy(int $id)
    {
        PaketSewa::findOrFail($id)->delete();
        return redirect()->route('admin.paket.index')->with('success', 'Paket sewa berhasil dihapus.');
    }
}

