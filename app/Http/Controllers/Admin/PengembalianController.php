<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengembalian;
use App\Models\Pengaturan;
use App\Models\Transaksi;
use Illuminate\Http\Request;

class PengembalianController extends Controller
{
    public function index(Request $request)
    {
        $setting = Pengaturan::getSetting();
        $query = Transaksi::with(['user', 'pengembalian'])
            ->whereIn('status_transaksi', ['Berjalan', 'Selesai'])
            ->orderByDesc('id_transaksi');

        if ($request->filled('status')) {
            $query->where('status_transaksi', $request->status);
        }

        $transaksiList = $query->paginate(15)->withQueryString();
        return view('admin.pengembalian.index', compact('setting', 'transaksiList'));
    }

    public function show(int $id)
    {
        $setting = Pengaturan::getSetting();
        $transaksi = Transaksi::with(['user', 'detailTransaksi.alatHiking', 'pengembalian'])
            ->findOrFail($id);
        return view('admin.pengembalian.show', compact('setting', 'transaksi'));
    }

    public function proses(Request $request, int $id)
    {
        $transaksi = Transaksi::with('detailTransaksi')->findOrFail($id);

        $request->validate([
            'tanggal_pengembalian_aktual' => 'required|date',
            'kondisi_pengembalian'        => 'required|string|max:100',
            'jumlah_baik'   => 'required|integer|min:0',
            'jumlah_rusak'  => 'required|integer|min:0',
            'jumlah_hilang' => 'required|integer|min:0',
            'catatan'       => 'nullable|string',
        ]);

        $setting = Pengaturan::getSetting();
        $tglKembali   = $transaksi->tgl_kembali;
        $tglAktual    = $request->tanggal_pengembalian_aktual;
        $dendaHarian  = $setting->denda_keterlambatan_per_hari;
        $hariTerlambat = max(0, now()->parse($tglKembali)->diffInDays(now()->parse($tglAktual), false));
        $denda = max(0, $hariTerlambat) * $dendaHarian;

        Pengembalian::updateOrCreate(
            ['id_transaksi' => $id],
            [
                'tanggal_pengembalian_aktual' => $tglAktual,
                'kondisi_pengembalian'        => $request->kondisi_pengembalian,
                'jumlah_baik'   => $request->jumlah_baik,
                'jumlah_rusak'  => $request->jumlah_rusak,
                'jumlah_hilang' => $request->jumlah_hilang,
                'denda'         => $denda,
                'catatan'       => $request->catatan,
                'id_admin'      => auth()->id(),
            ]
        );

        $transaksi->update([
            'status_transaksi' => 'Selesai',
            'denda'            => $denda,
        ]);

        return redirect()->route('admin.pengembalian.index')->with('success', 'Pengembalian berhasil diproses.');
    }
}

