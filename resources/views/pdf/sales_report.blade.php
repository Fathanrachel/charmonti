<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $title }}</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 13px; color: #333; line-height: 1.5; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #f3bec7; padding-bottom: 15px; }
        .header h1 { margin: 0; font-size: 22px; color: #1b1b18; }
        .header p { margin: 5px 0 0 0; font-size: 11px; color: #888; font-style: italic; }
        .info-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .info-table td { padding: 4px 0; border: none; }
        .report-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .report-table th { background-color: #fff2f2; border: 1px solid #e3e3e0; padding: 10px; text-align: left; font-weight: bold; font-size: 12px; color: #f53003; }
        .report-table td { border: 1px solid #e3e3e0; padding: 10px; font-size: 12px; }
        .report-table tr:nth-child(even) { background-color: #fafaf9; }
        .total-box { margin-top: 25px; text-align: right; font-weight: bold; font-size: 14px; color: #1b1b18; padding: 10px; border-top: 2px dashed #f3bec7; }
    </style>
</head>
<body>

    <div class="header">
        <h1>{{ $title }}</h1>
        <p>Dicetak pada: {{ $date }} &bull; Charm.onti</p>
    </div>

    <table class="report-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal Laporan</th>
                <th>Total Transaksi (Orders)</th>
                <th>Total Pendapatan</th>
            </tr>
        </thead>
        <tbody>
            @php $grandTotalOrders = 0; $grandTotalRevenue = 0; @endphp
            @forelse($reports as $index => $report)
                @php 
                    $grandTotalOrders += $report->total_orders; 
                    $grandTotalRevenue += $report->total_revenue; 
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($report->date)->translatedFormat('d F Y') }}</td>
                    <td>{{ $report->total_orders }} Transaksi</td>
                    <td>Rp {{ number_format($report->total_revenue, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align: center; color: #888;">Belum ada data laporan penjualan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="total-box">
        Ringkasan Akumulasi: {{ $grandTotalOrders }} Transaksi &bull; Pendapatan: Rp {{ number_format($grandTotalRevenue, 0, ',', '.') }}
    </div>

</body>
</html>
