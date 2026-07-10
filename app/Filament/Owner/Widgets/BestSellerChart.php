<?php

namespace App\Filament\Owner\Widgets;

use App\Models\OrderItem;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class BestSellerChart extends ChartWidget
{
    protected ?string $heading = '5 Produk Gelang Terlaris (Unit Terjual)';
    protected static ?int $sort = 6;
    protected int | string | array $columnSpan = 6;

    protected function getData(): array
    {
        // 1. Get total sales for catalog products from completed orders
        $catalogSales = OrderItem::select('product_id', DB::raw('SUM(qty) as total_qty'))
            ->whereHas('order', function ($query) {
                $query->where('status', 'selesai');
            })
            ->groupBy('product_id')
            ->with('product')
            ->get()
            ->map(function ($item) {
                return [
                    'name' => $item->product?->product_name ?? 'Produk',
                    'qty' => (int) $item->total_qty
                ];
            })->toArray();

        // 2. Count total custom bracelets sold from completed orders
        $customSalesCount = \App\Models\CustomBahanOrder::whereHas('order', function ($query) {
                $query->where('status', 'selesai');
            })
            ->count();

        // 3. Combine both sales lists
        $combinedSales = $catalogSales;
        if ($customSalesCount > 0) {
            $combinedSales[] = [
                'name' => 'Gelang Custom',
                'qty' => $customSalesCount
            ];
        }

        // 4. Sort descending by sales quantity and take top 5
        usort($combinedSales, function ($a, $b) {
            return $b['qty'] <=> $a['qty'];
        });
        $topSales = array_slice($combinedSales, 0, 5);

        $labels = [];
        $data = [];

        foreach ($topSales as $item) {
            $labels[] = $item['name'];
            $data[] = $item['qty'];
        }

        if (empty($labels)) {
            $labels = ['Belum ada transaksi selesai'];
            $data = [0];
        }

        return [
            'datasets' => [
                [
                    'label' => 'Total Unit Terjual',
                    'data' => $data,
                    'backgroundColor' => [
                        '#fbbf24', // Amber-400
                        '#f59e0b', // Amber-500
                        '#d97706', // Amber-600
                        '#b45309', // Amber-700
                        '#78350f', // Amber-900
                    ],
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
