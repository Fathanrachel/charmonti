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
        
        /* Kotak Summary (Grid 3 Kolom) */
        .summary-table { width: 100%; border-collapse: collapse; margin-top: 25px; }
        .summary-card { padding: 12px; border: 1px solid #e5e7eb; border-radius: 8px; }
        .card-income { background-color: #fffbeb; border-color: #fde68a; } /* Amber-50 */
        .card-expense { background-color: #fef2f2; border-color: #fecaca; } /* Red-50 */
        .card-profit { background-color: #f0fdf4; border-color: #bbf7d0; } /* Green-50 */
        
        .card-title { font-size: 9px; color: #7c2d12; text-transform: uppercase; font-weight: bold; margin-bottom: 4px; letter-spacing: 0.5px; }
        .card-title.expense { color: #7f1d1d; }
        .card-title.profit { color: #14532d; }
        .card-value { font-size: 15px; font-weight: bold; color: #1f2937; }
        .card-value.profit { color: #16a34a; }
        
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
                <div class="doc-title">Laporan Keuangan</div>
                <p>Penyajian: <strong>Per {{ $periodLabel }}</strong></p>
                <p>Dicetak: {{ $date }}</p>
            </td>
        </tr>
    </table>

    <!-- Tabel Data -->
    <table class="report-table">
        <thead>
            <tr>
                <th class="align-center" style="width: 8%;">No</th>
                <th style="width: 32%;">Periode / Tanggal</th>
                <th class="align-right" style="width: 20%;">Pendapatan (Income)</th>
                <th class="align-right" style="width: 20%;">Pengeluaran (Expense)</th>
                <th class="align-right" style="width: 20%;">Keuntungan Bersih (Profit)</th>
            </tr>
        </thead>
        <tbody>
            @php $grandIncome = 0; $grandExpense = 0; $grandProfit = 0; @endphp
            @forelse($reports as $index => $report)
                @php 
                    $grandIncome += $report->income; 
                    $grandExpense += $report->expense; 
                    $grandProfit += $report->profit; 
                    
                    // Format date
                    $formattedDate = $report->formatted_date ?? \Carbon\Carbon::parse($report->date)->translatedFormat('d F Y');
                @endphp
                <tr>
                    <td class="align-center">{{ $index + 1 }}</td>
                    <td>{{ $formattedDate }}</td>
                    <td class="align-right">Rp {{ number_format($report->income, 0, ',', '.') }}</td>
                    <td class="align-right">Rp {{ number_format($report->expense, 0, ',', '.') }}</td>
                    <td class="align-right font-bold" style="color: #16a34a;">Rp {{ number_format($report->profit, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="align-center" style="color: #9ca3af; padding: 20px;">Belum ada data laporan keuangan untuk periode ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Kartu Summary (Grid 3 Kolom menggunakan Table) -->
    <table class="summary-table">
        <tr>
            <td style="width: 32%; padding-right: 10px;">
                <div class="summary-card card-income">
                    <div class="card-title">TOTAL PENDAPATAN (REVENUE)</div>
                    <div class="card-value">Rp {{ number_format($grandIncome, 0, ',', '.') }}</div>
                </div>
            </td>
            <td style="width: 32%; padding-left: 5px; padding-right: 5px;">
                <div class="summary-card card-expense">
                    <div class="card-title expense">TOTAL PENGELUARAN (EXPENSE)</div>
                    <div class="card-value">Rp {{ number_format($grandExpense, 0, ',', '.') }}</div>
                </div>
            </td>
            <td style="width: 32%; padding-left: 10px;">
                <div class="summary-card card-profit">
                    <div class="card-title profit">KEUNTUNGAN BERSIH (NET PROFIT)</div>
                    <div class="card-value profit">Rp {{ number_format($grandProfit, 0, ',', '.') }}</div>
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
