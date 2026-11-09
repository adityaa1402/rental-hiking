<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AlatHiking;
use App\Models\DetailTransaksi;
use App\Models\Pengaturan;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

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
        $transaksi = Transaksi::with([
            'user',
            'detailTransaksi.alatHiking',
            'pengembalian.detailPengembalian.alatHiking',
            'dokumenJaminan',
            'pembayaran.admin',
        ])
            ->findOrFail($id);

        return view('admin.transaksi.show', compact('setting', 'transaksi'));
    }

    public function approve(Request $request, int $id)
    {
        $error = DB::transaction(function () use ($id): ?string {
            $transaksi = Transaksi::with('detailTransaksi')
                ->whereKey($id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($transaksi->status_transaksi !== 'Pending') {
                return 'Hanya transaksi Pending yang dapat disetujui.';
            }

            $itemIds = $transaksi->detailTransaksi->pluck('id_alat')->unique()->sort()->values();
            if ($itemIds->isEmpty()) {
                return 'Transaksi tidak memiliki rincian barang dan tidak dapat disetujui.';
            }

            $alatById = AlatHiking::whereIn('id_alat', $itemIds)
                ->orderBy('id_alat')
                ->lockForUpdate()
                ->get()
                ->keyBy('id_alat');

            foreach ($transaksi->detailTransaksi as $item) {
                $alat = $alatById->get($item->id_alat);
                if (!$alat) {
                    return 'Barang pada transaksi tidak ditemukan.';
                }

                $sisa = $alat->getSisaStok(
                    $transaksi->tgl_sewa->toDateString(),
                    $transaksi->tgl_kembali->toDateString(),
                    $transaksi->id_transaksi
                );
                if ($sisa < $item->jumlah_unit) {
                    return "Stok '{$alat->nama_alat}' tidak mencukupi (tersedia: {$sisa} unit).";
                }
            }

            $transaksi->update([
                'status_transaksi' => 'Disetujui',
                'catatan_admin'    => 'Transaksi disetujui',
            ]);

            return null;
        });

        if ($error) {
            return back()->with('error', $error);
        }

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
        $error = DB::transaction(function () use ($id): ?string {
            $transaksi = Transaksi::with('pembayaran')
                ->whereKey($id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($transaksi->status_transaksi !== 'Disetujui') {
                return 'Hanya transaksi yang disetujui dapat dimulai.';
            }

            if ($transaksi->pembayaran?->status_pembayaran !== 'Confirmed') {
                return 'Sewa hanya dapat dimulai setelah pembayaran dikonfirmasi.';
            }

            if ($transaksi->tgl_sewa->startOfDay()->isAfter(now()->startOfDay())) {
                return 'Sewa baru dapat dimulai pada tanggal mulai sewa: ' . $transaksi->tgl_sewa->format('d/m/Y') . '.';
            }

            if ($transaksi->metode_pengambilan === 'Kirim via Kurir') {
                return 'Transaksi kurir harus diproses melalui alur pengiriman.';
            }

            $transaksi->update([
                'status_transaksi' => 'Berjalan',
                'catatan_admin'    => 'Barang telah diserahkan',
            ]);

            return null;
        });

        if ($error) {
            return back()->with('error', $error);
        }

        return back()->with('success', 'Sewa dimulai! Status transaksi: Berjalan');
    }

    public function updateDelivery(Request $request, int $id)
    {
        $validated = $request->validate([
            'nama_kurir' => 'nullable|string|max:100',
            'telepon_kurir' => 'nullable|string|max:25',
            'nomor_resi' => 'nullable|string|max:100',
        ]);

        $nextStatus = DB::transaction(function () use ($validated, $id): string {
            $transaksi = Transaksi::with('pembayaran')
                ->whereKey($id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($transaksi->metode_pengambilan !== 'Kirim via Kurir') {
                throw ValidationException::withMessages([
                    'pengiriman' => 'Alur pengiriman hanya tersedia untuk transaksi kurir.',
                ]);
            }
            if ($transaksi->status_transaksi !== 'Disetujui' || $transaksi->pembayaran?->status_pembayaran !== 'Confirmed') {
                throw ValidationException::withMessages([
                    'pengiriman' => 'Pengiriman dapat dimulai setelah transaksi disetujui dan pembayaran dikonfirmasi.',
                ]);
            }

            $nextStatuses = [
                'Disiapkan' => 'Dalam Perjalanan',
                'Dalam Perjalanan' => 'Terkirim',
            ];
            $nextStatus = $transaksi->status_pengiriman === null
                ? 'Disiapkan'
                : ($nextStatuses[$transaksi->status_pengiriman] ?? null);

            if (!$nextStatus) {
                throw ValidationException::withMessages([
                    'pengiriman' => 'Pengiriman transaksi ini sudah selesai atau statusnya tidak valid.',
                ]);
            }
            if (
                $nextStatus === 'Terkirim'
                && $transaksi->tgl_sewa->startOfDay()->isAfter(now()->startOfDay())
            ) {
                throw ValidationException::withMessages([
                    'pengiriman' => 'Barang tidak dapat ditandai terkirim sebelum tanggal mulai sewa.',
                ]);
            }
            if ($nextStatus === 'Disiapkan' && empty($validated['nama_kurir'])) {
                throw ValidationException::withMessages([
                    'nama_kurir' => 'Nama kurir wajib diisi saat menyiapkan pengiriman.',
                ]);
            }

            $updates = [
                'status_pengiriman' => $nextStatus,
                'nama_kurir' => $validated['nama_kurir'] ?? $transaksi->nama_kurir,
                'telepon_kurir' => $validated['telepon_kurir'] ?? $transaksi->telepon_kurir,
                'nomor_resi' => $validated['nomor_resi'] ?? $transaksi->nomor_resi,
                'catatan_admin' => match ($nextStatus) {
                    'Disiapkan' => 'Pesanan kurir sedang disiapkan.',
                    'Dalam Perjalanan' => 'Pesanan sudah dikirim dan sedang dalam perjalanan.',
                    'Terkirim' => 'Pesanan telah diterima di lokasi pelanggan.',
                },
            ];

            if ($nextStatus === 'Dalam Perjalanan') {
                $updates['dikirim_pada'] = now();
            }

            if ($nextStatus === 'Terkirim') {
                $updates['diterima_pada'] = now();
                $updates['status_transaksi'] = 'Berjalan';
            }

            $transaksi->update($updates);

            return $nextStatus;
        });

        return back()->with('success', "Status pengiriman diperbarui: {$nextStatus}.");
    }

    public function complete(Request $request, int $id)
    {
        $transaksi = Transaksi::findOrFail($id);

        if ($transaksi->status_transaksi !== 'Berjalan') {
            return back()->with('error', 'Hanya transaksi yang sedang berjalan yang dapat diselesaikan.');
        }

        return redirect()->route('admin.pengembalian.show', $transaksi->id_transaksi)
            ->with('info', 'Periksa kondisi dan jumlah barang sebelum menyelesaikan pengembalian.');
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
