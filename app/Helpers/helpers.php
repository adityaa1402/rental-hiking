<?php

/**
 * HIKETRIP RENTAL - Laravel Helper Functions
 * Migrasi dari PHP murni ke Laravel
 */

if (!function_exists('formatRupiah')) {
    /**
     * Format angka ke format mata uang Rupiah Indonesia
     */
    function formatRupiah($amount): string
    {
        return 'Rp ' . number_format((float)$amount, 0, ',', '.');
    }
}

if (!function_exists('formatDate')) {
    /**
     * Format tanggal Indonesia (Contoh: 31 Oktober 2026)
     */
    function formatDate(?string $dateStr, bool $withTime = false): string
    {
        if (empty($dateStr)) return '-';
        
        try {
            $date = \Carbon\Carbon::parse($dateStr);
            
            $months = [
                1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
            ];
            
            $formatted = $date->format('d') . ' ' . $months[(int)$date->format('m')] . ' ' . $date->format('Y');
            
            if ($withTime) {
                $formatted .= ' ' . $date->format('H:i') . ' WIB';
            }
            
            return $formatted;
        } catch (\Exception $e) {
            return '-';
        }
    }
}

if (!function_exists('calculateRentalDays')) {
    /**
     * Hitung selisih hari antara dua tanggal sewa (minimal 1 hari)
     */
    function calculateRentalDays(string $startDate, string $endDate): int
    {
        try {
            $start = \Carbon\Carbon::parse($startDate);
            $end = \Carbon\Carbon::parse($endDate);
            
            if ($end <= $start) {
                return 1;
            }
            
            return max(1, $start->diffInDays($end));
        } catch (\Exception $e) {
            return 1;
        }
    }
}

if (!function_exists('generateTransactionCode')) {
    /**
     * Generate Kode Transaksi Unik: HKT-YYYYMMDD-XXXX
     */
    function generateTransactionCode(): string
    {
        $datePart = date('Ymd');
        $prefix = "HKT-$datePart-";
        
        $count = \App\Models\Transaksi::where('kode_transaksi', 'like', $prefix . '%')->count() + 1;
        
        return sprintf("%s%03d", $prefix, $count);
    }
}

if (!function_exists('getShopSettings')) {
    /**
     * Dapatkan pengaturan toko dari database
     */
    function getShopSettings(): array
    {
        static $settings = null;
        
        if ($settings === null) {
            $dbSettings = \App\Models\Pengaturan::first();
            
            if (!$dbSettings) {
                $settings = [
                    'nama_toko' => 'HIKETRIP RENTAL',
                    'tagline' => 'Gear Up, Explore Further.',
                    'alamat' => 'Jl. Lawu Raya No. 45, Karanganyar, Jawa Tengah',
                    'telepon' => '0271-889977',
                    'whatsapp' => '081234567890',
                    'email' => 'kontak@hikettrip.com',
                    'jam_operasional' => 'Setiap Hari 08:00 - 21:00 WIB',
                    'biaya_pengiriman_flat' => 25000,
                    'denda_keterlambatan_per_hari' => 50000,
                    'syarat_ketentuan' => 'Penyewa wajib membawa kartu identitas asli (KTP/SIM) saat serah terima barang.'
                ];
            } else {
                $settings = $dbSettings->toArray();
            }
        }
        
        return $settings;
    }
}

if (!function_exists('getItemAvailability')) {
    /**
     * LOGIKA STOK & KETERSEDIAAN:
     * Menghitung sisa stok barang yang benar-benar siap disewa pada rentang tanggal tertentu.
     * 
     * Sisa = Stok fisik layak sewa - Unit teralokasi pada transaksi 'Disetujui' dan 'Berjalan'
     * yang periode sewanya bertabrakan (overlap) dengan tanggal yang diinginkan.
     * Rumus Overlap: (tgl_sewa <= $endDate) AND (tgl_kembali >= $startDate)
     * 
     * @param int $id_alat
     * @param string|null $startDate (Format Y-m-d)
     * @param string|null $endDate (Format Y-m-d)
     * @param int|null $excludeTransactionId (Digunakan saat edit/persetujuan transaksi tertentu)
     * @return array ['stok_total' => int, 'stok_rusak' => int, 'stok_terpakai' => int, 'sisa_stok' => int, 'kondisi' => string]
     */
    function getItemAvailability(int $id_alat, ?string $startDate = null, ?string $endDate = null, ?int $excludeTransactionId = null): array
    {
        $item = \App\Models\AlatHiking::find($id_alat);
        
        if (!$item) {
            return ['stok_total' => 0, 'stok_rusak' => 0, 'stok_terpakai' => 0, 'sisa_stok' => 0, 'kondisi' => 'Tidak Ditemukan'];
        }
        
        $stokTotal = max(0, (int) $item->stok_total - (int) $item->stok_rusak);
        $kondisi = $item->kondisi;
        
        // Jika kondisi bukan 'Baik', alat tidak bisa disewakan
        if ($kondisi !== 'Baik') {
            return [
                'stok_total' => (int) $item->stok_total,
                'stok_rusak' => (int) $item->stok_rusak,
                'stok_terpakai' => 0,
                'sisa_stok' => 0,
                'kondisi' => $kondisi,
            ];
        }
        
        // Jika tanggal sewa tidak ditentukan, ambil peminjaman aktif saat ini (hari ini)
        if (empty($startDate) || empty($endDate)) {
            $today = date('Y-m-d');
            $startDate = $today;
            $endDate = $today;
        }
        
        // Hitung total unit yang sedang terikat dalam transaksi Disetujui atau Berjalan pada periode tersebut
        $query = \App\Models\DetailTransaksi::join('transaksi', 'detail_transaksi.id_transaksi', '=', 'transaksi.id_transaksi')
            ->where('detail_transaksi.id_alat', $id_alat)
            ->whereIn('transaksi.status_transaksi', ['Disetujui', 'Berjalan'])
            ->where('transaksi.tgl_sewa', '<=', $endDate)
            ->where('transaksi.tgl_kembali', '>=', $startDate);
        
        if ($excludeTransactionId !== null) {
            $query->where('transaksi.id_transaksi', '!=', $excludeTransactionId);
        }
        
        $terpakai = (int)$query->sum('detail_transaksi.jumlah_unit');
        $sisaStok = max(0, $stokTotal - $terpakai);
        
        return [
            'stok_total'    => (int) $item->stok_total,
            'stok_rusak'    => (int) $item->stok_rusak,
            'stok_terpakai' => $terpakai,
            'sisa_stok'     => $sisaStok,
            'kondisi'       => $kondisi
        ];
    }
}

if (!function_exists('validateOrderItemsAvailability')) {
    /**
     * Validasi ketersediaan stok seluruh item dalam sebuah pesanan sebelum disetujui
     * Digunakan untuk mencegah DOUBLE BOOKING dengan database transaction locking
     */
    function validateOrderItemsAvailability(int $id_transaksi): array
    {
        $transaksi = \App\Models\Transaksi::find($id_transaksi);
        
        if (!$transaksi) {
            return ['valid' => false, 'message' => 'Transaksi tidak ditemukan.'];
        }
        
        $items = \App\Models\DetailTransaksi::where('id_transaksi', $id_transaksi)
            ->with('alatHiking')
            ->get();
        
        foreach ($items as $item) {
            $avail = getItemAvailability(
                (int)$item->id_alat, 
                $transaksi->tgl_sewa, 
                $transaksi->tgl_kembali, 
                $id_transaksi
            );
            
            if ($avail['sisa_stok'] < (int)$item->jumlah_unit) {
                return [
                    'valid' => false,
                    'message' => "Stok '{$item->alatHiking->nama_alat}' tidak mencukupi untuk periode {$transaksi->tgl_sewa} s/d {$transaksi->tgl_kembali}. Dibutuhkan {$item->jumlah_unit} unit, sisa tersedia {$avail['sisa_stok']} unit."
                ];
            }
        }
        
        return ['valid' => true, 'message' => 'Stok tersedia.'];
    }
}

if (!function_exists('renderStatusBadge')) {
    /**
     * Badge status transaksi Bootstrap 5
     */
    function renderStatusBadge(string $status): string
    {
        switch ($status) {
            case 'Pending':
                return '<span class="badge bg-warning text-dark px-3 py-2"><i class="bi bi-clock-history me-1"></i> Menunggu Persetujuan</span>';
            case 'Disetujui':
                return '<span class="badge bg-info text-dark px-3 py-2"><i class="bi bi-check2-circle me-1"></i> Disetujui (Siap Diambil)</span>';
            case 'Berjalan':
                return '<span class="badge bg-primary px-3 py-2"><i class="bi bi-arrow-repeat me-1"></i> Sedang Disewa</span>';
            case 'Selesai':
                return '<span class="badge bg-success px-3 py-2"><i class="bi bi-check-all me-1"></i> Selesai (Sudah Kembali)</span>';
            case 'Ditolak':
                return '<span class="badge bg-danger px-3 py-2"><i class="bi bi-x-circle me-1"></i> Ditolak</span>';
            case 'Dibatalkan':
                return '<span class="badge bg-secondary px-3 py-2"><i class="bi bi-slash-circle me-1"></i> Dibatalkan</span>';
            default:
                return '<span class="badge bg-secondary px-3 py-2">' . e($status) . '</span>';
        }
    }
}

if (!function_exists('getProductImageUrl')) {
    /**
     * Get product image URL from gallery
     * Handles storage disk properly
     */
    function getProductImageUrl($item): string
    {
        if (!$item || !isset($item->id_alat)) {
            return asset('assets/images/logo.svg');
        }

        $gallery = \App\Models\BarangGallery::where('id_alat', $item->id_alat)
            ->orderBy('urutan')
            ->first();

        foreach ([$item->gambar, $gallery?->gambar] as $image) {
            if (!$image) {
                continue;
            }

            $image = ltrim(str_replace('\\', '/', $image), '/');

            if (is_file(public_path($image))) {
                return asset($image);
            }

            $uploadPath = 'uploads/barang/' . basename($image);
            if (is_file(public_path($uploadPath))) {
                return asset($uploadPath);
            }

            if (\Illuminate\Support\Facades\Storage::disk('public')->exists($image)) {
                return \Illuminate\Support\Facades\Storage::disk('public')->url($image);
            }
        }

        return asset('assets/images/logo.svg');
    }
}
