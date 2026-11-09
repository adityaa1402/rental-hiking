<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use App\Models\Pengaturan;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    /**
     * List all pending payments for verification
     */
    public function index(Request $request)
    {
        $setting = Pengaturan::getSetting();
        
        $query = Pembayaran::with('transaksi.user', 'admin')
            ->where('status_pembayaran', 'Pending')
            ->orderByDesc('created_at');

        // Search by transaction code or customer name
        if ($request->filled('q')) {
            $q = $request->q;
            $query->whereHas('transaksi', function($sq) use ($q) {
                $sq->where('kode_transaksi', 'like', "%{$q}%")
                   ->orWhereHas('user', fn($uq) => $uq->where('nama_lengkap', 'like', "%{$q}%"));
            });
        }

        $pembayarans = $query->paginate(15)->withQueryString();
        
        return view('admin.payment.index', compact('setting', 'pembayarans'));
    }

    /**
     * Show payment details for verification
     */
    public function show(int $id)
    {
        $setting = Pengaturan::getSetting();
        
        $pembayaran = Pembayaran::with([
            'transaksi.user',
            'transaksi.detailTransaksi.alatHiking',
            'admin'
        ])->findOrFail($id);
        
        return view('admin.payment.show', compact('setting', 'pembayaran'));
    }

    public function proof(int $id)
    {
        $pembayaran = Pembayaran::findOrFail($id);
        $path = $pembayaran->bukti_pembayaran;
        $directory = 'bukti-pembayaran/';

        abort_unless(
            is_string($path)
                && str_starts_with($path, $directory)
                && basename($path) === substr($path, strlen($directory)),
            404
        );

        $disk = Storage::disk('public');
        abort_unless($disk->exists($path), 404);

        $mimeType = $disk->mimeType($path);
        abort_unless(in_array($mimeType, ['image/jpeg', 'image/png', 'image/webp'], true), 404);

        return response()->file($disk->path($path), [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    /**
     * Approve payment
     */
    public function approve(Request $request, int $id)
    {
        $error = DB::transaction(function () use ($id): ?string {
            $paymentIdTransaksi = Pembayaran::whereKey($id)->value('id_transaksi');
            abort_unless($paymentIdTransaksi, 404);
            $transaksi = Transaksi::whereKey($paymentIdTransaksi)
                ->lockForUpdate()
                ->firstOrFail();
            $pembayaran = Pembayaran::whereKey($id)->lockForUpdate()->firstOrFail();

            if ($pembayaran->status_pembayaran !== 'Pending') {
                return 'Hanya pembayaran pending yang dapat dikonfirmasi.';
            }
            if ($transaksi->status_transaksi !== 'Disetujui') {
                return 'Pembayaran hanya dapat dikonfirmasi setelah transaksi disetujui.';
            }
            if ($pembayaran->metode_pembayaran === 'Tunai di Toko') {
                if ($transaksi->metode_pengambilan !== 'Ambil di Toko') {
                    return 'Pembayaran tunai hanya berlaku untuk pengambilan di toko.';
                }
            } elseif (!$pembayaran->bukti_pembayaran) {
                return 'Bukti pembayaran online belum diunggah pelanggan.';
            }

            $pembayaran->update([
                'status_pembayaran'    => 'Confirmed',
                'dikonfirmasi_oleh'    => auth()->id(),
                'tanggal_dikonfirmasi' => now(),
                'catatan_admin'        => 'Pembayaran dikonfirmasi oleh ' . auth()->user()->nama_lengkap,
            ]);

            return null;
        });

        if ($error) {
            return back()->with('error', $error);
        }

        return back()->with('success', 'Pembayaran berhasil dikonfirmasi! Transaksi siap diproses.');
    }

    /**
     * Reject payment
     */
    public function reject(Request $request, int $id)
    {
        $request->validate([
            'alasan_penolakan' => 'required|string|max:500',
        ], [
            'alasan_penolakan.required' => 'Alasan penolakan wajib diisi.',
        ]);

        $error = DB::transaction(function () use ($id, $request): ?string {
            $paymentIdTransaksi = Pembayaran::whereKey($id)->value('id_transaksi');
            abort_unless($paymentIdTransaksi, 404);
            $transaksi = Transaksi::whereKey($paymentIdTransaksi)
                ->lockForUpdate()
                ->firstOrFail();
            $pembayaran = Pembayaran::whereKey($id)->lockForUpdate()->firstOrFail();

            if ($pembayaran->status_pembayaran !== 'Pending') {
                return 'Hanya pembayaran pending yang dapat ditolak.';
            }
            if ($transaksi->status_transaksi !== 'Disetujui') {
                return 'Pembayaran hanya dapat diverifikasi setelah transaksi disetujui.';
            }

            $pembayaran->update([
                'status_pembayaran'    => 'Rejected',
                'dikonfirmasi_oleh'    => auth()->id(),
                'tanggal_dikonfirmasi' => now(),
                'catatan_admin'        => $request->alasan_penolakan,
            ]);

            return null;
        });

        if ($error) {
            return back()->with('error', $error);
        }

        return back()->with('success', 'Pembayaran ditolak. Customer dapat mengupload bukti lagi.');
    }

    /**
     * History of all payments (approved, rejected, etc)
     */
    public function history(Request $request)
    {
        $setting = Pengaturan::getSetting();
        
        $query = Pembayaran::with('transaksi.user', 'admin')
            ->where('status_pembayaran', '!=', 'Pending')
            ->orderByDesc('tanggal_dikonfirmasi');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status_pembayaran', $request->status);
        }

        // Search
        if ($request->filled('q')) {
            $q = $request->q;
            $query->whereHas('transaksi', function($sq) use ($q) {
                $sq->where('kode_transaksi', 'like', "%{$q}%")
                   ->orWhereHas('user', fn($uq) => $uq->where('nama_lengkap', 'like', "%{$q}%"));
            });
        }

        $pembayarans = $query->paginate(15)->withQueryString();
        
        return view('admin.payment.history', compact('setting', 'pembayarans'));
    }
}
