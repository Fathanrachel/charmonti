<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $title }}</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 12px; color: #374151; line-height: 1.6; }
        
        /* Kop Surat Resmi */
        .kop-table { width: 100%; border-collapse: collapse; margin-bottom: 15px; border-bottom: 2px solid #ec4899; }
        .kop-left { padding-bottom: 15px; }
        .kop-left h1 { margin: 0; font-size: 26px; color: #ec4899; font-weight: bold; letter-spacing: -0.5px; }
        .kop-left p { margin: 2px 0 0 0; font-size: 11px; color: #6b7280; line-height: 1.4; }
        .kop-right { text-align: right; vertical-align: bottom; padding-bottom: 15px; }
        .kop-right .doc-title { font-size: 15px; font-weight: bold; color: #1f2937; margin: 0 0 4px 0; text-transform: uppercase; }
        .kop-right p { margin: 0; font-size: 10px; color: #6b7280; }

        /* Tabel Rapi */
        .report-table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        .report-table th { background-color: #ec4899; border: 1px solid #fbcfe8; padding: 10px 8px; text-align: left; font-weight: bold; font-size: 11px; color: #ffffff; text-transform: uppercase; }
        .report-table td { border: 1px solid #f3e8ff; padding: 10px 8px; font-size: 11px; color: #374151; }
        .report-table tr:nth-child(even) { background-color: #fdf2f8; }
        
        /* Alignments */
        .align-center { text-align: center; }
        .align-right { text-align: right; }
        .font-bold { font-weight: bold; }
        
        /* Kotak Summary (Grid 2 Kolom) */
        .summary-table { width: 100%; border-collapse: collapse; margin-top: 25px; }
        .summary-card { padding: 12px; border: 1px solid #e5e7eb; border-radius: 8px; }
        .card-orders { background-color: #f3f4f6; border-color: #d1d5db; } /* Gray-100 */
        .card-revenue { background-color: #f0fdf4; border-color: #bbf7d0; } /* Green-50 */
        
        .card-title { font-size: 9px; color: #4b5563; text-transform: uppercase; font-weight: bold; margin-bottom: 4px; letter-spacing: 0.5px; }
        .card-title.revenue { color: #14532d; }
        .card-value { font-size: 15px; font-weight: bold; color: #1f2937; }
        .card-value.revenue { color: #16a34a; }
        
        /* Tanda Tangan */
        .signature-section { width: 100%; margin-top: 40px; border-collapse: collapse; page-break-inside: avoid; }
        .signature-space { height: 50px; }
        .sig-text { font-size: 11px; color: #374151; }
    </style>
</head>
<body>

    <!-- Kop Surat Resmi -->
    <table class="kop-table">
        <tr>
            <td class="kop-left">
                <h1>CharmOnTi</h1>
                <p>Spesialis Aksesoris Manik-manik Premium, Gelang Jadi, & Custom<br>
                Jakarta, Indonesia | Kontak: racheltambunan10@gmail.com</p>
            </td>
            <td class="kop-right">
                <div class="doc-title">Laporan Penjualan</div>
                <p>Penyajian: <strong>Per {{ $periodLabel }}</strong></p>
                <p>Dicetak: {{ $date }}</p>
            </td>
        </tr>
    </table>

    <!-- Tabel Data -->
    <table class="report-table">
        <thead>
            <tr>
                <th class="align-center" style="width: 10%;">No</th>
                <th style="width: 40%;">Tanggal Laporan</th>
                <th class="align-center" style="width: 25%;">Total Transaksi (Orders)</th>
                <th class="align-right" style="width: 25%;">Total Pendapatan (Revenue)</th>
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
                    <td class="align-center">{{ $index + 1 }}</td>
                    <td>{{ $report->formatted_date ?? \Carbon\Carbon::parse($report->date)->translatedFormat('d F Y') }}</td>
                    <td class="align-center">{{ $report->total_orders }} Transaksi</td>
                    <td class="align-right font-bold">Rp {{ number_format($report->total_revenue, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="align-center" style="color: #9ca3af; padding: 20px;">Belum ada data laporan penjualan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Kartu Summary (Grid 2 Kolom menggunakan Table) -->
    <table class="summary-table">
        <tr>
            <td style="width: 50%; padding-right: 10px;">
                <div class="summary-card card-orders">
                    <div class="card-title">TOTAL TRANSAKSI BERHASIL</div>
                    <div class="card-value">{{ $grandTotalOrders }} Transaksi</div>
                </div>
            </td>
            <td style="width: 50%; padding-left: 10px;">
                <div class="summary-card card-revenue">
                    <div class="card-title revenue">AKUMULASI PENDAPATAN (GROSS REVENUE)</div>
                    <div class="card-value revenue">Rp {{ number_format($grandTotalRevenue, 0, ',', '.') }}</div>
                </div>
            </td>
        </tr>
    </table>

    <!-- Area Tanda Tangan -->
    <table class="signature-section">
        <tr>
            <td style="width: 65%;"></td>
            <td style="width: 35%; text-align: center;" class="sig-text">
                Jakarta, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}<br>
                <strong>Owner CharmOnTi</strong>
            </td>
        </tr>
    </table>

</body>
</html>
