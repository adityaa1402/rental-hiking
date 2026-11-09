<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use App\Models\Pengaturan;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    /**
     * Show payment form for customer to upload proof
     */
    public function show(int $id)
    {
        $setting = Pengaturan::getSetting();
        
        $transaksi = Transaksi::with(['pembayaran', 'detailTransaksi.alatHiking', 'dokumenJaminan'])
            ->where('id_user', auth()->user()->id)
            ->where('id_transaksi', $id)
            ->where('status_transaksi', 'Disetujui')
            ->firstOrFail();

        if ($transaksi->pembayaran?->status_pembayaran === 'Confirmed') {
            return redirect()->route('customer.transaksi.show', $id)
                ->with('success', 'Pembayaran transaksi ini sudah dikonfirmasi.');
        }
        
        // Get atau create empty pembayaran record jika belum ada
        $pembayaran = $transaksi->pembayaran;
        
        return view('customer.payment.show', compact('setting', 'transaksi', 'pembayaran'));
    }

    /**
     * Store payment proof submission
     */
    public function store(Request $request, int $id)
    {
        $transaksi = Transaksi::where('id_user', auth()->user()->id)
            ->where('id_transaksi', $id)
            ->where('status_transaksi', 'Disetujui')
            ->firstOrFail();

        if (Pembayaran::where('id_transaksi', $id)->where('status_pembayaran', 'Confirmed')->exists()) {
            return redirect()->route('customer.transaksi.show', $id)
                ->with('success', 'Pembayaran transaksi ini sudah dikonfirmasi dan tidak dapat diubah.');
        }

        $metodeOnline = ['Transfer Bank', 'E-Wallet'];
        $metodeDiizinkan = $transaksi->metode_pengambilan === 'Kirim via Kurir'
            ? $metodeOnline
            : [...$metodeOnline, 'Tunai di Toko'];
        $isOnline = in_array($request->input('metode_pembayaran'), $metodeOnline, true);

        $request->validate([
            'metode_pembayaran' => ['required', Rule::in($metodeDiizinkan)],
            'bukti_pembayaran'  => [$isOnline ? 'required' : 'nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:3072'],
            'keterangan'        => 'nullable|string|max:500',
        ], [
            'metode_pembayaran.in' => 'Pengiriman dengan kurir hanya dapat dibayar secara online. Pembayaran tunai hanya tersedia untuk pengambilan di toko.',
            'bukti_pembayaran.required' => 'Upload bukti transfer/e-wallet untuk melanjutkan pembayaran online.',
            'bukti_pembayaran.image' => 'File harus berupa gambar.',
            'bukti_pembayaran.mimes' => 'Format gambar harus JPG, PNG, atau WEBP.',
            'bukti_pembayaran.max'   => 'Ukuran gambar maksimal 3MB.',
        ]);

        // Handle file upload
        $buktiPath = null;
        if ($request->hasFile('bukti_pembayaran')) {
            $file = $request->file('bukti_pembayaran');
            $fileName = 'bukti_' . $id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $buktiPath = $file->storeAs('bukti-pembayaran', $fileName, 'public');
        }

        $result = DB::transaction(function () use ($id, $buktiPath, $request): array|false {
            $transaksi = Transaksi::where('id_user', auth()->id())
                ->whereKey($id)
                ->lockForUpdate()
                ->firstOrFail();
            if ($transaksi->status_transaksi !== 'Disetujui') {
                return false;
            }

            $pembayaran = Pembayaran::where('id_transaksi', $id)
                ->lockForUpdate()
                ->first();
            if ($pembayaran?->status_pembayaran === 'Confirmed') {
                return false;
            }

            $oldProofPath = $pembayaran?->bukti_pembayaran;
            $pembayaran ??= new Pembayaran(['id_transaksi' => $id]);
            $pembayaran->fill([
                'jumlah'                  => $transaksi->total_harga,
                'metode_pembayaran'       => $request->metode_pembayaran,
                'status_pembayaran'       => 'Pending',
                'bukti_pembayaran'        => $buktiPath,
                'keterangan_pembayaran'   => $request->keterangan,
            ]);
            $pembayaran->save();

            return ['old_proof_path' => $oldProofPath];
        });

        if ($result === false) {
            if ($buktiPath) {
                Storage::disk('public')->delete($buktiPath);
            }

            return redirect()->route('customer.transaksi.show', $id)
                ->with('error', 'Transaksi atau pembayaran sudah berubah. Muat ulang halaman sebelum mencoba lagi.');
        }

        if ($result['old_proof_path'] && $result['old_proof_path'] !== $buktiPath) {
            Storage::disk('public')->delete($result['old_proof_path']);
        }

        $message = $isOnline
            ? 'Bukti pembayaran online berhasil dikirim. Mohon menunggu verifikasi admin.'
            : 'Pilihan pembayaran tunai di toko berhasil dicatat. Pembayaran akan dikonfirmasi saat pengambilan.';

        return redirect()->route('customer.transaksi.show', $id)->with('success', $message);
    }
}
