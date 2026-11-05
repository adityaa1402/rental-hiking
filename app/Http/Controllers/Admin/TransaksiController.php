<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AlatHiking;
use App\Models\DetailTransaksi;
use App\Models\Pengaturan;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    public function index(Request $request)
    {
        $setting = Pengaturan::getSetting();
        $query = Transaksi::with('user')->orderByDesc('id_transaksi');

        if ($request->filled('status')) {
            $query->where('status_transaksi', $request->status);
        }
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(fn($sq) => $sq->where('kode_transaksi', 'like', "%{$q}%")
                ->orWhereHas('user', fn($uq) => $uq->where('nama_lengkap', 'like', "%{$q}%")));
        }

        $transaksis = $query->paginate(15)->withQueryString();
        return view('admin.transaksi.index', compact('setting', 'transaksis'));
    }

    public function show(int $id)
    {
        $setting = Pengaturan::getSetting();
        $transaksi = Transaksi::with(['user', 'detailTransaksi.alatHiking', 'pengembalian', 'dokumenJaminan'])
            ->findOrFail($id);

        return view('admin.transaksi.show', compact('setting', 'transaksi'));
    }

    public function approve(Request $request, int $id)
    {
        $transaksi = Transaksi::findOrFail($id);

        if ($transaksi->status_transaksi !== 'Pending') {
            return back()->with('error', 'Hanya transaksi Pending yang dapat disetujui.');
        }

        // Validasi stok
        foreach ($transaksi->detailTransaksi as $item) {
            $alat = AlatHiking::find($item->id_alat);
            $sisa = $alat->getSisaStok($transaksi->tgl_sewa, $transaksi->tgl_kembali, $transaksi->id_transaksi);
            if ($sisa < $item->jumlah_unit) {
                return back()->with('error', "Stok '{$alat->nama_barang}' tidak mencukupi (tersedia: {$sisa} unit).");
            }
        }

        $transaksi->update([
            'status_transaksi' => 'Disetujui',
            'catatan_admin'    => 'Transaksi disetujui',
        ]);

        return back()->with('success', 'Transaksi berhasil disetujui!');
    }

    public function reject(Request $request, int $id)
    {
        $transaksi = Transaksi::findOrFail($id);

        if ($transaksi->status_transaksi !== 'Pending') {
            return back()->with('error', 'Hanya transaksi Pending yang dapat ditolak.');
        }

        $request->validate([
            'alasan' => 'required|string|max:500',
        ]);

        $transaksi->update([
            'status_transaksi' => 'Ditolak',
            'catatan_admin'    => 'Ditolak: ' . $request->alasan,
        ]);

        return back()->with('success', 'Transaksi berhasil ditolak!');
    }

    public function start(Request $request, int $id)
    {
        $transaksi = Transaksi::findOrFail($id);

        if ($transaksi->status_transaksi !== 'Disetujui') {
            return back()->with('error', 'Hanya transaksi yang disetujui dapat dimulai.');
        }

        $transaksi->update([
            'status_transaksi' => 'Berjalan',
            'catatan_admin'    => 'Barang telah diserahkan',
        ]);

        return back()->with('success', 'Sewa dimulai! Status transaksi: Berjalan');
    }

    public function complete(Request $request, int $id)
    {
        $transaksi = Transaksi::findOrFail($id);

        if ($transaksi->status_transaksi !== 'Berjalan') {
            return back()->with('error', 'Hanya transaksi yang sedang berjalan yang dapat diselesaikan.');
        }

        $transaksi->update([
            'status_transaksi' => 'Selesai',
            'catatan_admin'    => 'Sewa selesai',
        ]);

        return back()->with('success', 'Transaksi berhasil diselesaikan!');
    }

    public function aksi(Request $request, int $id)
    {
        $transaksi = Transaksi::findOrFail($id);
        $aksi = $request->aksi;

        $allowed = match ($transaksi->status_transaksi) {
            'Pending'   => ['Disetujui', 'Ditolak'],
            'Disetujui' => ['Berjalan', 'Ditolak'],
            'Berjalan'  => ['Selesai'],
            default     => [],
        };

        if (!in_array($aksi, $allowed)) {
            return back()->with('error', 'Aksi tidak valid untuk status saat ini.');
        }

        // Validasi stok saat menyetujui
        if ($aksi === 'Disetujui') {
            foreach ($transaksi->detailTransaksis as $item) {
                $alat = AlatHiking::find($item->id_alat);
                $sisa = $alat->getSisaStok($transaksi->tgl_sewa, $transaksi->tgl_kembali, $transaksi->id_transaksi);
                if ($sisa < $item->jumlah_unit) {
                    return back()->with('error', "Stok '{$alat->nama_barang}' tidak mencukupi (tersedia: {$sisa} unit).");
                }
            }
        }

        $transaksi->update([
            'status_transaksi' => $aksi,
            'catatan_admin'    => $request->catatan_admin ?? $transaksi->catatan_admin,
        ]);

        return back()->with('success', "Status transaksi berhasil diubah ke: {$aksi}.");
    }
}

