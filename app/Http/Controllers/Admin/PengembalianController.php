<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengembalian;
use App\Models\Pengaturan;
use App\Models\Transaksi;
use App\Models\AlatHiking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PengembalianController extends Controller
{
    public function show(int $id)
    {
        $setting = Pengaturan::getSetting();
        $transaksi = Transaksi::with([
            'user',
            'detailTransaksi.alatHiking',
            'pengembalian.detailPengembalian.alatHiking',
        ])
            ->findOrFail($id);
        abort_unless(in_array($transaksi->status_transaksi, ['Berjalan', 'Selesai'], true), 404);

        $jumlahDisewa = $transaksi->detailTransaksi->sum('jumlah_unit');
        $barangDisewa = $transaksi->detailTransaksi
            ->groupBy('id_alat')
            ->map(fn ($details) => [
                'alat' => $details->first()->alatHiking,
                'jumlah' => (int) $details->sum('jumlah_unit'),
            ]);
        $dendaHarian = $setting->denda_keterlambatan_per_hari;

        return view('admin.pengembalian.show', compact(
            'setting',
            'transaksi',
            'jumlahDisewa',
            'barangDisewa',
            'dendaHarian'
        ));
    }

    public function proses(Request $request, int $id)
    {
        $request->validate([
            'tanggal_pengembalian_aktual' => 'required|date|before_or_equal:today',
            'kondisi_pengembalian'        => 'required|string|max:100',
            'barang' => 'required|array|min:1',
            'barang.*.id_alat' => 'required|integer|distinct|exists:alat_hiking,id_alat',
            'barang.*.jumlah_baik' => 'required|integer|min:0',
            'barang.*.jumlah_rusak' => 'required|integer|min:0',
            'barang.*.jumlah_hilang' => 'required|integer|min:0',
            'catatan'       => 'nullable|string|max:2000',
        ], [
            'tanggal_pengembalian_aktual.before_or_equal' => 'Tanggal pengembalian aktual tidak boleh di masa depan. Isi tanggal saat barang benar-benar diterima kembali.',
        ]);

        DB::transaction(function () use ($request, $id): void {
            $transaksi = Transaksi::with(['detailTransaksi', 'pembayaran'])
                ->whereKey($id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($transaksi->status_transaksi !== 'Berjalan') {
                throw ValidationException::withMessages([
                    'pengembalian' => 'Pengembalian hanya dapat diproses untuk transaksi yang sedang berjalan.',
                ]);
            }
            if ($transaksi->pembayaran?->status_pembayaran !== 'Confirmed') {
                throw ValidationException::withMessages([
                    'pengembalian' => 'Pengembalian hanya dapat diproses setelah pembayaran dikonfirmasi.',
                ]);
            }
            if (
                $transaksi->metode_pengambilan === 'Kirim via Kurir'
                && $transaksi->status_pengiriman !== 'Terkirim'
            ) {
                throw ValidationException::withMessages([
                    'pengembalian' => 'Masa sewa kurir baru dimulai setelah barang ditandai terkirim.',
                ]);
            }

            $tglAktual = $request->date('tanggal_pengembalian_aktual');
            if ($tglAktual->lt(\Carbon\Carbon::parse($transaksi->tgl_sewa)->startOfDay())) {
                throw ValidationException::withMessages([
                    'tanggal_pengembalian_aktual' => 'Tanggal pengembalian aktual tidak boleh sebelum tanggal mulai sewa.',
                ]);
            }

            $jumlahPerAlat = $transaksi->detailTransaksi
                ->groupBy('id_alat')
                ->map(fn ($details) => (int) $details->sum('jumlah_unit'));
            if ($jumlahPerAlat->isEmpty()) {
                throw ValidationException::withMessages([
                    'pengembalian' => 'Transaksi ini tidak memiliki barang sewaan untuk diproses.',
                ]);
            }

            $barangInput = collect($request->input('barang', []))
                ->keyBy(fn (array $item) => (int) $item['id_alat']);
            if ($barangInput->keys()->sort()->values()->all() !== $jumlahPerAlat->keys()->sort()->values()->all()) {
                throw ValidationException::withMessages([
                    'barang' => 'Rincian pemeriksaan harus mencakup setiap jenis barang yang disewa tepat satu kali.',
                ]);
            }

            $jumlahBaik = 0;
            $jumlahRusak = 0;
            $jumlahHilang = 0;
            foreach ($jumlahPerAlat as $idAlat => $jumlahDisewa) {
                $item = $barangInput->get($idAlat);
                $jumlahDiperiksa = (int) $item['jumlah_baik']
                    + (int) $item['jumlah_rusak']
                    + (int) $item['jumlah_hilang'];
                if ($jumlahDiperiksa !== $jumlahDisewa) {
                    throw ValidationException::withMessages([
                        "barang.{$idAlat}.jumlah_baik" => "Jumlah baik, rusak, dan hilang untuk {$jumlahPerAlat[$idAlat]} unit harus tepat sama dengan jumlah yang disewa.",
                    ]);
                }

                $jumlahBaik += (int) $item['jumlah_baik'];
                $jumlahRusak += (int) $item['jumlah_rusak'];
                $jumlahHilang += (int) $item['jumlah_hilang'];
            }

            $alatById = AlatHiking::whereIn('id_alat', $jumlahPerAlat->keys()->sort()->values())
                ->orderBy('id_alat')
                ->lockForUpdate()
                ->get()
                ->keyBy('id_alat');
            foreach ($jumlahPerAlat as $idAlat => $jumlahDisewa) {
                $alat = $alatById->get($idAlat);
                if (!$alat) {
                    throw ValidationException::withMessages([
                        'barang' => 'Barang pada transaksi tidak ditemukan.',
                    ]);
                }

                $item = $barangInput->get($idAlat);
                $stokTotalBaru = (int) $alat->stok_total - (int) $item['jumlah_hilang'];
                $stokRusakBaru = (int) $alat->stok_rusak + (int) $item['jumlah_rusak'];
                if ($stokTotalBaru < $stokRusakBaru) {
                    throw ValidationException::withMessages([
                        "barang.{$idAlat}.jumlah_rusak" => "Stok layak untuk {$alat->nama_alat} tidak cukup untuk mencatat barang rusak/hilang ini.",
                    ]);
                }

                $alat->update([
                    'stok_total' => $stokTotalBaru,
                    'stok_rusak' => $stokRusakBaru,
                ]);
            }

            $setting = Pengaturan::getSetting();
            $hariTerlambat = max(
                0,
                (int) \Carbon\Carbon::parse($transaksi->tgl_kembali)
                    ->startOfDay()
                    ->diffInDays($tglAktual->copy()->startOfDay(), false)
            );
            $denda = $hariTerlambat * (float) $setting->denda_keterlambatan_per_hari;

            $pengembalian = Pengembalian::create([
                'id_transaksi' => $transaksi->id_transaksi,
                'tanggal_pengembalian_aktual' => $tglAktual->toDateString(),
                'kondisi_pengembalian' => $request->kondisi_pengembalian,
                'jumlah_baik' => $jumlahBaik,
                'jumlah_rusak' => $jumlahRusak,
                'jumlah_hilang' => $jumlahHilang,
                'denda' => $denda,
                'catatan' => $request->catatan,
                'id_admin' => auth()->id(),
            ]);

            foreach ($jumlahPerAlat as $idAlat => $jumlahDisewa) {
                $item = $barangInput->get($idAlat);
                $pengembalian->detailPengembalian()->create([
                    'id_alat' => $idAlat,
                    'jumlah_baik' => $item['jumlah_baik'],
                    'jumlah_rusak' => $item['jumlah_rusak'],
                    'jumlah_hilang' => $item['jumlah_hilang'],
                ]);
            }

            $transaksi->update([
                'status_transaksi' => 'Selesai',
                'denda' => $denda,
                'catatan_admin' => 'Pengembalian barang telah diperiksa.',
            ]);
        });

        return redirect()->route('admin.transaksi.show', $id)
            ->with('success', 'Pengembalian berhasil diproses.');
    }
}
