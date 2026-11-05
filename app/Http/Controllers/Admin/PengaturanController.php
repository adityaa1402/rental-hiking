<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengaturan;
use Illuminate\Http\Request;

class PengaturanController extends Controller
{
    public function index()
    {
        $setting = Pengaturan::getSetting();
        return view('admin.pengaturan.index', compact('setting'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'nama_toko'   => 'required|string|max:150',
            'tagline'     => 'nullable|string|max:255',
            'alamat'      => 'nullable|string',
            'telepon'     => 'nullable|string|max:25',
            'whatsapp'    => 'nullable|string|max:25',
            'email'       => 'nullable|email|max:100',
            'jam_operasional' => 'nullable|string|max:100',
            'biaya_pengiriman_flat' => 'required|numeric|min:0',
            'denda_keterlambatan_per_hari' => 'required|numeric|min:0',
            'syarat_ketentuan' => 'nullable|string',
        ]);

        $setting = Pengaturan::first();
        if ($setting) {
            $setting->update($request->except('_token', '_method'));
        } else {
            Pengaturan::create($request->except('_token', '_method'));
        }

        return back()->with('success', 'Pengaturan toko berhasil disimpan.');
    }
}

