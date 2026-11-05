<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengaturan;
use App\Models\Transaksi;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $setting = Pengaturan::getSetting();
        $query = Transaksi::with('user')->whereIn('status_transaksi', ['Selesai', 'Berjalan', 'Disetujui']);

        if ($request->filled('dari')) {
            $query->whereDate('created_at', '>=', $request->dari);
        }
        if ($request->filled('sampai')) {
            $query->whereDate('created_at', '<=', $request->sampai);
        }
        if ($request->filled('status')) {
            $query->where('status_transaksi', $request->status);
        }

        $transaksiList = $query->orderByDesc('id_transaksi')->paginate(20)->withQueryString();
        $totalPendapatan = $query->sum('total_harga');

        return view('admin.laporan.index', compact('setting', 'transaksiList', 'totalPendapatan'));
    }

    public function cetak(Request $request)
    {
        $setting = Pengaturan::getSetting();
        $query = Transaksi::with('user')->whereIn('status_transaksi', ['Selesai', 'Berjalan', 'Disetujui']);

        if ($request->filled('dari')) $query->whereDate('created_at', '>=', $request->dari);
        if ($request->filled('sampai')) $query->whereDate('created_at', '<=', $request->sampai);

        $transaksiList = $query->orderByDesc('id_transaksi')->get();
        $totalPendapatan = $transaksiList->sum('total_harga');

        return view('admin.laporan.cetak', compact('setting', 'transaksiList', 'totalPendapatan'));
    }

    public function exportCsv(Request $request)
    {
        $query = Transaksi::with('user')->whereIn('status_transaksi', ['Selesai', 'Berjalan', 'Disetujui']);

        if ($request->filled('dari')) $query->whereDate('created_at', '>=', $request->dari);
        if ($request->filled('sampai')) $query->whereDate('created_at', '<=', $request->sampai);

        $rows = $query->orderByDesc('id_transaksi')->get();

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="laporan_hiketrip_' . now()->format('Ymd') . '.csv"',
        ];

        $callback = function () use ($rows) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF)); // BOM UTF-8

            fputcsv($handle, ['Kode Transaksi', 'Nama Pelanggan', 'Email', 'Tgl Sewa', 'Tgl Kembali', 'Total Hari', 'Total Harga', 'Metode', 'Status']);
            foreach ($rows as $tx) {
                fputcsv($handle, [
                    $tx->kode_transaksi,
                    $tx->user->nama_lengkap ?? '-',
                    $tx->user->email ?? '-',
                    $tx->tgl_sewa->format('d/m/Y'),
                    $tx->tgl_kembali->format('d/m/Y'),
                    $tx->total_hari,
                    $tx->total_harga,
                    $tx->metode_pengambilan,
                    $tx->status_transaksi,
                ]);
            }
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}

