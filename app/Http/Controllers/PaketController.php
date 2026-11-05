<?php

namespace App\Http\Controllers;

use App\Models\PaketSewa;
use App\Models\Pengaturan;

class PaketController extends Controller
{
    public function index()
    {
        $setting = Pengaturan::getSetting();
        $packages = PaketSewa::where('status_aktif', 1)
            ->orderBy('harga_paket_per_hari')
            ->get();

        return view('paket.index', compact('setting', 'packages'));
    }

    public function show(string $slug)
    {
        $setting = Pengaturan::getSetting();
        $paket = PaketSewa::with(['detailPaket.alat.kategori'])
            ->where('slug', $slug)
            ->where('status_aktif', 1)
            ->firstOrFail();

        return view('paket.show', compact('setting', 'paket'));
    }
}

