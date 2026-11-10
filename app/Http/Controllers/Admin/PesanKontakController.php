<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PesanKontak;
use Illuminate\Http\Request;

class PesanKontakController extends Controller
{
    public function index(Request $request)
    {
        $query = PesanKontak::with('user');

        if ($request->filled('q')) {
            $search = $request->string('q')->toString();
            $query->where(function ($messages) use ($search) {
                $messages->where('nama', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('subjek', 'like', "%{$search}%");
            });
        }

        $pesanList = $query->latest()->paginate(15)->withQueryString();

        return view('admin.pesan.index', compact('pesanList'));
    }

    public function show(int $id)
    {
        $pesan = PesanKontak::with('user')->findOrFail($id);

        return view('admin.pesan.show', compact('pesan'));
    }
}
