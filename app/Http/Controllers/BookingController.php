<?php

namespace App\Http\Controllers;

use App\Models\AlatHiking;
use App\Models\DetailTransaksi;
use App\Models\DokumenJaminan;
use App\Models\Pengaturan;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BookingController extends Controller
{
    public function create(Request $request)
    {
        $setting = Pengaturan::getSetting();
        
        $alatId = (int)$request->get('alat_id', 0);
        $paketId = (int)$request->get('paket_id', 0);
        
        $selectedItem = null;
        $selectedPackage = null;
        $packageItems = [];
        
        if ($alatId > 0) {
            $selectedItem = AlatHiking::with('kategori')->find($alatId);
            if (!$selectedItem) {
                return redirect()->route('katalog.index')
                    ->with('error', 'Alat tidak ditemukan.');
            }
        } elseif ($paketId > 0) {
            $selectedPackage = \App\Models\PaketSewa::with('detailPaket.alatHiking')
                ->where('status_aktif', 1)
                ->find($paketId);
            
            if (!$selectedPackage) {
                return redirect()->route('paket.index')
                    ->with('error', 'Paket tidak ditemukan.');
            }
            
            $packageItems = $selectedPackage->detailPaket;
        } else {
            return redirect()->route('katalog.index')
                ->with('error', 'Silakan pilih alat atau paket untuk disewa.');
        }

        return view('booking.create', compact('setting', 'selectedItem', 'selectedPackage', 'packageItems'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'alat_id'           => 'nullable|integer|exists:alat_hiking,id_alat',
            'paket_id'          => 'nullable|integer|exists:paket_sewa,id_paket',
            'tgl_sewa'          => 'required|date|after_or_equal:today',
            'tgl_kembali'       => 'required|date|after:tgl_sewa',
            'jumlah_unit'       => 'required|integer|min:1|max:10',
            'metode_pengambilan'=> 'required|in:Ambil di Toko,Kirim via Kurir',
            'alamat_pengiriman' => 'required_if:metode_pengambilan,Kirim via Kurir|nullable|string',
            'catatan_customer'  => 'nullable|string|max:500',
            'tipe_dokumen'      => 'required|in:KTP,SIM,Paspor',
            'dokumen_jaminan'   => 'required|file|mimes:jpg,jpeg,png,pdf,webp|max:3072',
            'setuju_syarat'     => 'accepted',
        ], [
            'tgl_sewa.after_or_equal' => 'Tanggal sewa tidak boleh di masa lalu.',
            'tgl_kembali.after'       => 'Tanggal kembali harus setelah tanggal sewa.',
            'dokumen_jaminan.required'=> 'Dokumen jaminan (KTP/SIM) wajib diupload.',
            'dokumen_jaminan.max'     => 'Ukuran file maksimal 3MB.',
            'setuju_syarat.accepted'  => 'Anda harus menyetujui syarat & ketentuan.',
        ]);

        if ($request->filled('alat_id') === $request->filled('paket_id')) {
            return back()->withErrors([
                'alat_id' => 'Pilih tepat satu alat atau satu paket untuk disewa.',
            ])->withInput();
        }

        $setting = Pengaturan::getSetting();
        $tglSewa    = $request->tgl_sewa;
        $tglKembali = $request->tgl_kembali;
        $jumlahUnit = $request->jumlah_unit;
        $totalHari  = calculateRentalDays($tglSewa, $tglKembali);

        $subtotal = 0;
        $itemData = [];

        // Booking untuk single alat
        if ($request->filled('alat_id')) {
            $alat = AlatHiking::find($request->alat_id);
            if (!$alat) {
                return back()->withErrors(['alat_id' => 'Alat tidak ditemukan.'])->withInput();
            }

            $avail = getItemAvailability($alat->id_alat, $tglSewa, $tglKembali);
            if ($avail['sisa_stok'] < $jumlahUnit) {
                return back()->withErrors([
                    'jumlah_unit' => "Stok '{$alat->nama_alat}' tidak mencukupi. Tersedia: {$avail['sisa_stok']} unit."
                ])->withInput();
            }

            $hargaSnap = $alat->harga_per_hari;
            $sub = $hargaSnap * $jumlahUnit * $totalHari;
            $subtotal = $sub;
            
            $itemData[] = [
                'id_alat'                 => $alat->id_alat,
                'jumlah_unit'             => $jumlahUnit,
                'harga_per_hari_snapshot' => $hargaSnap,
                'subtotal'                => $sub,
            ];
        }

        // Booking untuk paket
        if ($request->filled('paket_id')) {
            $paket = \App\Models\PaketSewa::with('detailPaket.alatHiking')->find($request->paket_id);
            if (!$paket || !$paket->status_aktif) {
                return back()->withErrors(['paket_id' => 'Paket tidak ditemukan atau tidak aktif.'])->withInput();
            }
            if ($paket->detailPaket->isEmpty()) {
                return back()->withErrors(['paket_id' => 'Paket ini belum memiliki komponen barang dan belum dapat disewa.'])->withInput();
            }

            // Validasi stok semua komponen paket
            foreach ($paket->detailPaket as $detail) {
                $needed = $detail->jumlah_unit * $jumlahUnit;
                $avail = getItemAvailability($detail->id_alat, $tglSewa, $tglKembali);
                if ($avail['sisa_stok'] < $needed) {
                    return back()->withErrors([
                        'paket_id' => "Komponen '{$detail->alatHiking->nama_alat}' tidak mencukupi. Dibutuhkan: {$needed}, tersedia: {$avail['sisa_stok']}."
                    ])->withInput();
                }

                $hargaSnap = $detail->alatHiking->harga_per_hari;
                $sub = $hargaSnap * $needed * $totalHari;
                
                $itemData[] = [
                    'id_alat'                 => $detail->id_alat,
                    'jumlah_unit'             => $needed,
                    'harga_per_hari_snapshot' => $hargaSnap,
                    'subtotal'                => $sub,
                ];
            }
            
            // Gunakan harga paket untuk subtotal
            $subtotal = $paket->harga_paket_per_hari * $jumlahUnit * $totalHari;
        }

        $biayaPengiriman = $request->metode_pengambilan === 'Kirim via Kurir'
            ? $setting['biaya_pengiriman_flat']
            : 0;

        // Alamat pengiriman hanya disimpan jika metode adalah "Kirim via Kurir"
        $alamatPengiriman = $request->metode_pengambilan === 'Kirim via Kurir'
            ? $request->alamat_pengiriman
            : null;

        $totalHarga = $subtotal + $biayaPengiriman;

        DB::transaction(function () use ($request, $tglSewa, $tglKembali, $totalHari, $subtotal, $biayaPengiriman, $totalHarga, $itemData, $alamatPengiriman) {
            $transaksi = Transaksi::create([
                'kode_transaksi'     => generateTransactionCode(),
                'id_user'            => auth()->id(),
                'tgl_sewa'           => $tglSewa,
                'tgl_kembali'        => $tglKembali,
                'total_hari'         => $totalHari,
                'subtotal'           => $subtotal,
                'biaya_pengiriman'   => $biayaPengiriman,
                'diskon'             => 0,
                'denda'              => 0,
                'total_harga'        => $totalHarga,
                'metode_pengambilan' => $request->metode_pengambilan,
                'alamat_pengiriman'  => $alamatPengiriman,
                'status_transaksi'   => 'Pending',
                'catatan_customer'   => $request->catatan_customer,
            ]);

            foreach ($itemData as $item) {
                DetailTransaksi::create(array_merge($item, ['id_transaksi' => $transaksi->id_transaksi]));
            }

            // Upload dokumen jaminan
            if ($request->hasFile('dokumen_jaminan')) {
                $file = $request->file('dokumen_jaminan');
                $fileName = 'jaminan_' . $transaksi->id_transaksi . '_' . time() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('jaminan', $fileName, 'public');
                
                DokumenJaminan::create([
                    'id_transaksi'        => $transaksi->id_transaksi,
                    'nama_file_tersimpan' => $fileName,
                    'tipe_dokumen'        => $request->tipe_dokumen,
                ]);
            }
        });

        return redirect()->route('customer.transaksi.index')
            ->with('success', 'Booking berhasil dikirim! Mohon tunggu konfirmasi dari admin.');
    }
}
