<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AlatHiking;
use App\Models\PaketSewa;
use App\Models\Pengaturan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PaketAdminController extends Controller
{
    public function index()
    {
        $setting = Pengaturan::getSetting();
        $paketList = PaketSewa::withCount('detailPaket')->orderByDesc('id_paket')->paginate(15);
        return view('admin.paket.index', compact('setting', 'paketList'));
    }

    public function create()
    {
        $setting = Pengaturan::getSetting();
        $alatList = AlatHiking::with('kategori')->withoutDuplicateListings()->where('kondisi', 'Baik')->get();
        return view('admin.paket.create', compact('setting', 'alatList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_paket'            => 'required|string|max:150',
            'deskripsi'             => 'required|string',
            'kapasitas_orang'       => 'required|integer|min:1',
            'harga_paket_per_hari'  => 'required|numeric|min:0',
            'gambar'                => 'required|image|mimes:jpg,jpeg,png,webp|max:3072',
            'items'                 => 'required|array|min:1',
            'items.*.id_alat'       => 'required|integer|distinct|exists:alat_hiking,id_alat',
            'items.*.jumlah'        => 'required|integer|min:1',
        ]);

        $gambarPath = $request->file('gambar')->store('paket', 'public');
        DB::transaction(function () use ($validated, $request, $gambarPath): void {
            $paket = PaketSewa::create([
                'nama_paket'           => $validated['nama_paket'],
                'slug'                 => Str::limit(Str::slug($validated['nama_paket']), 140, '') . '-' . Str::lower(Str::random(8)),
                'deskripsi'            => $validated['deskripsi'],
                'kapasitas_orang'      => $validated['kapasitas_orang'],
                'harga_paket_per_hari' => $validated['harga_paket_per_hari'],
                'gambar'               => basename($gambarPath),
                'status_aktif'         => $request->boolean('status_aktif', true) ? 1 : 0,
            ]);

            foreach ($validated['items'] as $item) {
                $paket->detailPaket()->create([
                    'id_alat' => $item['id_alat'],
                    'jumlah_unit' => $item['jumlah'],
                ]);
            }
        });

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
        $existingItemIds = $paket->detailPaket->pluck('id_alat');
        $alatList = AlatHiking::with('kategori')
            ->where(function ($query) use ($existingItemIds): void {
                $query->where('kondisi', 'Baik')
                    ->orWhereIn('id_alat', $existingItemIds);
            })
            ->orderBy('nama_alat')
            ->get();
        return view('admin.paket.edit', compact('setting', 'paket', 'alatList'));
    }

    public function update(Request $request, int $id)
    {
        $paket = PaketSewa::findOrFail($id);
        $validated = $request->validate([
            'nama_paket'            => 'required|string|max:150',
            'deskripsi'             => 'required|string',
            'kapasitas_orang'       => 'required|integer|min:1',
            'harga_paket_per_hari'  => 'required|numeric|min:0',
            'gambar'                => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3072',
            'items'                 => 'required|array|min:1',
            'items.*.id_alat'       => 'required|integer|distinct|exists:alat_hiking,id_alat',
            'items.*.jumlah'        => 'required|integer|min:1',
        ]);

        $data = [
            'nama_paket' => $validated['nama_paket'],
            'deskripsi' => $validated['deskripsi'],
            'kapasitas_orang' => $validated['kapasitas_orang'],
            'harga_paket_per_hari' => $validated['harga_paket_per_hari'],
            'status_aktif' => $request->boolean('status_aktif') ? 1 : 0,
        ];

        if ($request->hasFile('gambar')) {
            $gambarPath = $request->file('gambar')->store('paket', 'public');
            $data['gambar'] = basename($gambarPath);
        }

        DB::transaction(function () use ($paket, $data, $validated): void {
            $paket->update($data);
            $paket->detailPaket()->delete();

            foreach ($validated['items'] as $item) {
                $paket->detailPaket()->create([
                    'id_alat' => $item['id_alat'],
                    'jumlah_unit' => $item['jumlah'],
                ]);
            }
        });

        return redirect()->route('admin.paket.index')->with('success', 'Paket sewa berhasil diperbarui.');
    }

    public function destroy(int $id)
    {
        PaketSewa::findOrFail($id)->delete();
        return redirect()->route('admin.paket.index')->with('success', 'Paket sewa berhasil dihapus.');
    }
}
