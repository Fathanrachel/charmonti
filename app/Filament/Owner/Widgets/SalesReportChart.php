<?php

namespace App\Filament\Owner\Widgets;

use App\Models\Order;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class SalesReportChart extends ChartWidget
{
    protected ?string $heading = 'Tren Pendapatan Bulanan (Omzet)';
    protected static ?int $sort = 5;
    protected int | string | array $columnSpan = 6;

    protected function getData(): array
    {
        // Ambil semua pesanan selesai di tahun ini
        $orders = Order::where('status', 'selesai')
            ->whereYear('order_date', date('Y'))
            ->get();

        // Inisialisasi data bulanan (1 = Januari, 12 = Desember)
        $monthlyRevenue = array_fill(1, 12, 0);

        foreach ($orders as $order) {
            $monthNum = (int) Carbon::parse($order->order_date)->format('n');
            $monthlyRevenue[$monthNum] += (float) $order->total_price;
        }

        $monthsLabel = [
            'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun',
            'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'
        ];

        return [
            'datasets' => [
                [
                    'label' => 'Pendapatan Bersih (Rp)',
                    'data' => array_values($monthlyRevenue),
                    'borderColor' => '#e11d48', // Tailwind Rose-600
                    'backgroundColor' => 'rgba(253, 244, 245, 0.5)',
                    'fill' => true,
                ],
            ],
            'labels' => $monthsLabel,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
