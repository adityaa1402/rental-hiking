<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Pengaturan;
use App\Models\Transaksi;

class DashboardController extends Controller
{
    public function index()
    {
        $setting = Pengaturan::getSetting();
        $user = auth()->user();

        $stats = [
            'total'      => Transaksi::where('id_user', $user->id_user)->count(),
            'pending'    => Transaksi::where('id_user', $user->id_user)->where('status_transaksi', 'Pending')->count(),
            'berjalan'   => Transaksi::where('id_user', $user->id_user)->where('status_transaksi', 'Berjalan')->count(),
            'selesai'    => Transaksi::where('id_user', $user->id_user)->where('status_transaksi', 'Selesai')->count(),
        ];

        $recentTransaksis = Transaksi::where('id_user', $user->id_user)
            ->orderByDesc('id_transaksi')
            ->limit(5)
            ->get();

        return view('customer.dashboard', compact('setting', 'user', 'stats', 'recentTransaksis'));
    }
}

