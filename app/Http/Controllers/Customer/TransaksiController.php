<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Pengaturan;
use App\Models\Transaksi;
use Illuminate\Http\Request;

class TransaksiController extends Controller
{
    public function index(Request $request)
    {
        $setting = Pengaturan::getSetting();
        $user = auth()->user();
        $query = Transaksi::where('id_user', $user->id_user)
            ->with('detailTransaksi')
            ->orderByDesc('id_transaksi');

        if ($request->filled('status')) {
            $query->where('status_transaksi', $request->status);
        }

        $transaksis = $query->paginate(10)->withQueryString();
        
        return view('customer.transaksi.index', compact('setting', 'transaksis'));
    }

    public function show(int $id)
    {
        $setting = Pengaturan::getSetting();
        $transaksi = Transaksi::with(['detailTransaksi.alatHiking', 'pengembalian', 'dokumenJaminan'])
            ->where('id_user', auth()->user()->id_user)
            ->where('id_transaksi', $id)
            ->firstOrFail();

        return view('customer.transaksi.show', compact('setting', 'transaksi'));
    }

    public function batalkan(Request $request, int $id)
    {
        $transaksi = Transaksi::where('id_user', auth()->user()->id_user)
            ->where('id_transaksi', $id)
            ->where('status_transaksi', 'Pending')
            ->firstOrFail();

        $transaksi->update(['status_transaksi' => 'Dibatalkan']);

        return redirect()->route('customer.transaksi.show', $id)
            ->with('success', 'Transaksi berhasil dibatalkan.');
    }
}

