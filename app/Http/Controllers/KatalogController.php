<?php

namespace App\Http\Controllers;

use App\Models\AlatHiking;
use App\Models\Kategori;
use App\Models\Pengaturan;
use Illuminate\Http\Request;

class KatalogController extends Controller
{
    public function index(Request $request)
    {
        $setting = Pengaturan::getSetting();
        $query = AlatHiking::with('kategori')->where('kondisi', 'Baik');

        // Search
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sq) use ($q) {
                $sq->where('nama_alat', 'like', "%{$q}%")
                   ->orWhere('merk', 'like', "%{$q}%")
                   ->orWhere('deskripsi', 'like', "%{$q}%");
            });
        }

        // Filter by kategori
        if ($request->filled('kategori')) {
            $query->whereHas('kategori', fn($sq) => $sq->where('slug', $request->kategori));
        }

        // Sorting
        $sort = $request->get('sort', 'terbaru');
        switch ($sort) {
            case 'harga_asc':
                $query->orderBy('harga_per_hari', 'ASC');
                break;
            case 'harga_desc':
                $query->orderBy('harga_per_hari', 'DESC');
                break;
            case 'nama_asc':
                $query->orderBy('nama_alat', 'ASC');
                break;
            default:
                $query->orderBy('id_alat', 'DESC');
                break;
        }

        $items = $query->paginate(9)->withQueryString();
        $allKategori = Kategori::orderBy('nama_kategori')->get();

        return view('katalog.index', compact('setting', 'items', 'allKategori'));
    }

    public function show(string $slug)
    {
        $setting = Pengaturan::getSetting();
        $alat = AlatHiking::with('kategori')->where('slug', $slug)->firstOrFail();
        $related = AlatHiking::with('kategori')
            ->where('id_kategori', $alat->id_kategori)
            ->where('id_alat', '!=', $alat->id_alat)
            ->where('kondisi', 'Baik')
            ->limit(4)
            ->get();

        return view('katalog.show', compact('setting', 'alat', 'related'));
    }
}

