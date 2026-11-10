<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AlatHiking;
use App\Models\Pengaturan;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $setting = Pengaturan::getSetting();

        // Stats untuk cards
        $stats = [
            'total_barang' => AlatHiking::withoutDuplicateListings()->count(),
            'total_unit' => AlatHiking::withoutDuplicateListings()->sum('stok_total'),
            'transaksi_pending' => Transaksi::where('status_transaksi', 'Pending')->count(),
            'transaksi_disetujui' => Transaksi::where('status_transaksi', 'Disetujui')->count(),
            'transaksi_berjalan' => Transaksi::where('status_transaksi', 'Berjalan')->count(),
            'transaksi_selesai' => Transaksi::where('status_transaksi', 'Selesai')->count(),
            'total_pendapatan' => Transaksi::whereIn('status_transaksi', ['Disetujui', 'Berjalan', 'Selesai'])->sum('total_harga'),
            'pendapatan_bulan' => Transaksi::whereIn('status_transaksi', ['Disetujui', 'Berjalan', 'Selesai'])
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('total_harga'),
            'total_customer' => User::where('role', 'customer')->count(),
        ];

        $unitDisewa = DB::table('detail_transaksi as dt')
            ->join('transaksi as t', 'dt.id_transaksi', '=', 't.id_transaksi')
            ->where('t.status_transaksi', 'Berjalan')
            ->sum('dt.jumlah_unit');

        $kembaliHariIni = Transaksi::where('status_transaksi', 'Berjalan')
            ->whereDate('tgl_kembali', today())
            ->count();

        $grafikData = Transaksi::selectRaw("DATE_FORMAT(created_at, '%Y-%m') as bulan, DATE_FORMAT(created_at, '%b %Y') as label_bulan, COUNT(*) as total_tx, COALESCE(SUM(total_harga),0) as total_nominal")
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('bulan', 'label_bulan')
            ->orderBy('bulan')
            ->get();

        $recentTransaksis = Transaksi::with('user')
            ->orderByDesc('id_transaksi')
            ->limit(6)
            ->get();

        return view('admin.dashboard', compact(
            'setting', 'stats', 'unitDisewa', 'kembaliHariIni', 'grafikData', 'recentTransaksis'
        ));
    }
}
