<?php

namespace App\Http\Controllers;

use App\Models\AlatHiking;
use App\Models\Kategori;
use App\Models\PaketSewa;
use App\Models\Pengaturan;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $setting = Pengaturan::getSetting();
        $kategoriList = Kategori::withCount([
            'alatHiking as alat_hiking_count' => fn ($query) => $query
                ->withoutDuplicateListings()
                ->where('kondisi', 'Baik'),
        ])->orderBy('id_kategori')->get();
        $produkPopuler = AlatHiking::withoutDuplicateListings()
            ->with('kategori')
            ->where('kondisi', 'Baik')
            ->orderBy('id_alat')
            ->limit(6)
            ->get();
        $paketList = PaketSewa::with('detailPaket.alatHiking')
            ->where('status_aktif', 1)
            ->orderBy('harga_paket_per_hari')
            ->limit(4)
            ->get();
        $paketDiskonTerbesar = 0;

        foreach ($paketList as $paket) {
            $hargaNormal = $paket->detailPaket->sum(
                fn ($detail) => ($detail->alatHiking?->harga_per_hari ?? 0) * $detail->jumlah_unit
            );

            if ($hargaNormal > 0) {
                $penghematan = max(0, $hargaNormal - $paket->harga_paket_per_hari);
                $paketDiskonTerbesar = max($paketDiskonTerbesar, (int) round($penghematan / $hargaNormal * 100));
            }
        }

        return view('home', compact('setting', 'kategoriList', 'produkPopuler', 'paketList', 'paketDiskonTerbesar'));
    }

    public function tentang()
    {
        $setting = Pengaturan::getSetting();
        return view('tentang', compact('setting'));
    }

    public function kontak(Request $request)
    {
        $setting = Pengaturan::getSetting();
        return view('kontak', compact('setting'));
    }

    public function kontakSend(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'email' => 'required|email|max:100',
            'subjek' => 'required|string|max:200',
            'pesan' => 'required|string|max:1000',
        ], [
            'nama.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'subjek.required' => 'Subjek pesan wajib diisi.',
            'pesan.required' => 'Isi pesan wajib diisi.',
        ]);

        // Dalam implementasi real, kirim email atau simpan ke database
        // Untuk sekarang, hanya redirect dengan success message

        return redirect()->route('kontak')->with('success', 'Pesan Anda berhasil dikirim!');
    }
}
