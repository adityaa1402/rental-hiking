<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Transaksi - {{ $setting->nama_toko ?? 'HIKETRIP' }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; margin: 20px; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .header h2 { margin: 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { background-color: #f0f0f0; padding: 10px; text-align: left; border-bottom: 1px solid #333; font-weight: bold; }
        td { padding: 8px; border-bottom: 1px solid #ddd; }
        tr:hover { background-color: #f9f9f9; }
        .total { font-weight: bold; background-color: #f0f0f0; }
        .footer { margin-top: 30px; text-align: center; font-size: 11px; color: #666; }
    </style>
</head>
<body>
    <div class="header">
        <h2>{{ $setting->nama_toko ?? 'HIKETRIP RENTAL' }}</h2>
        <p>Laporan Transaksi</p>
        <p>Cetak: {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Kode Transaksi</th>
                <th>Pelanggan</th>
                <th>Periode Sewa</th>
                <th>Hari</th>
                <th>Total</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @forelse($transaksiList as $tx)
                <tr>
                    <td>{{ $tx->kode_transaksi }}</td>
                    <td>{{ $tx->user->nama_lengkap ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($tx->tgl_sewa)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($tx->tgl_kembali)->format('d/m/Y') }}</td>
                    <td>{{ $tx->total_hari }}</td>
                    <td>Rp{{ number_format($tx->total_harga, 0, ',', '.') }}</td>
                    <td>{{ $tx->status_transaksi }}</td>
                </tr>
                @php $no++; @endphp
            @empty
                <tr>
                    <td colspan="6" style="text-align:center;">Tidak ada data</td>
                </tr>
            @endforelse
            <tr class="total">
                <td colspan="4" style="text-align: right;">TOTAL:</td>
                <td>Rp{{ number_format($totalPendapatan, 0, ',', '.') }}</td>
                <td></td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        <p>Laporan ini dihasilkan otomatis dari sistem {{ $setting->nama_toko ?? 'HIKETRIP' }} pada {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <script>
        window.print();
    </script>
</body>
</html>
