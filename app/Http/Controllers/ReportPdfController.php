<?php

namespace App\Http\Controllers;

use App\Models\SalesReport;
use App\Models\FinancialReport;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class ReportPdfController extends Controller
{
    public function downloadSalesReport(Request $request)
    {
        $period = $request->input('period', 'daily');
        $query = SalesReport::query();

        if ($period === 'weekly') {
            $reports = $query->selectRaw("
                MIN(date) as date,
                CONCAT('Minggu ke-', TO_CHAR(date, 'WW, YYYY')) as formatted_date,
                SUM(total_orders) as total_orders,
                SUM(total_revenue) as total_revenue
            ")
            ->groupByRaw("TO_CHAR(date, 'WW, YYYY')")
            ->orderBy('date', 'desc')
            ->get();
            $periodLabel = 'Mingguan';
        } elseif ($period === 'monthly') {
            $reports = $query->selectRaw("
                MIN(date) as date,
                TO_CHAR(date, 'Month YYYY') as formatted_date,
                SUM(total_orders) as total_orders,
                SUM(total_revenue) as total_revenue
            ")
            ->groupByRaw("TO_CHAR(date, 'YYYY-MM')")
            ->orderBy('date', 'desc')
            ->get();
            $periodLabel = 'Bulanan';
        } elseif ($period === 'yearly') {
            $reports = $query->selectRaw("
                MIN(date) as date,
                TO_CHAR(date, 'YYYY') as formatted_date,
                SUM(total_orders) as total_orders,
                SUM(total_revenue) as total_revenue
            ")
            ->groupByRaw("TO_CHAR(date, 'YYYY')")
            ->orderBy('date', 'desc')
            ->get();
            $periodLabel = 'Tahunan';
        } else {
            $reports = $query->orderBy('date', 'desc')->get();
            $periodLabel = 'Harian';
        }

        $pdf = Pdf::loadView('pdf.sales_report', [
            'reports' => $reports,
            'periodLabel' => $periodLabel,
            'period' => $period,
            'title' => 'Laporan Penjualan (Sales Report)',
            'date' => Carbon::now()->translatedFormat('d F Y H:i')
        ]);

        return $pdf->download('sales_report_' . $period . '_' . now()->format('YmdHis') . '.pdf');
    }

    public function downloadFinancialReport(Request $request)
    {
        $period = $request->input('period', 'daily');
        $query = FinancialReport::query();

        if ($period === 'weekly') {
            $reports = $query->selectRaw("
                MIN(date) as date,
                CONCAT('Minggu ke-', TO_CHAR(date, 'WW, YYYY')) as formatted_date,
                SUM(income) as income,
                SUM(expense) as expense,
                SUM(profit) as profit
            ")
            ->groupByRaw("TO_CHAR(date, 'WW, YYYY')")
            ->orderBy('date', 'desc')
            ->get();
            $periodLabel = 'Mingguan';
        } elseif ($period === 'monthly') {
            $reports = $query->selectRaw("
                MIN(date) as date,
                TO_CHAR(date, 'Month YYYY') as formatted_date,
                SUM(income) as income,
                SUM(expense) as expense,
                SUM(profit) as profit
            ")
            ->groupByRaw("TO_CHAR(date, 'YYYY-MM')")
            ->orderBy('date', 'desc')
            ->get();
            $periodLabel = 'Bulanan';
        } elseif ($period === 'yearly') {
            $reports = $query->selectRaw("
                MIN(date) as date,
                TO_CHAR(date, 'YYYY') as formatted_date,
                SUM(income) as income,
                SUM(expense) as expense,
                SUM(profit) as profit
            ")
            ->groupByRaw("TO_CHAR(date, 'YYYY')")
            ->orderBy('date', 'desc')
            ->get();
            $periodLabel = 'Tahunan';
        } else {
            $reports = $query->orderBy('date', 'desc')->get();
            $periodLabel = 'Harian';
        }

        $pdf = Pdf::loadView('pdf.financial_report', [
            'reports' => $reports,
            'periodLabel' => $periodLabel,
            'period' => $period,
            'title' => 'Laporan Keuangan (Financial Report)',
            'date' => Carbon::now()->translatedFormat('d F Y H:i')
        ]);

        return $pdf->download('financial_report_' . $period . '_' . now()->format('YmdHis') . '.pdf');
    }
}
