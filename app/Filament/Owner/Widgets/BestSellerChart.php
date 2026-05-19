<?php

namespace App\Filament\Owner\Widgets;

use App\Models\OrderItem;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class BestSellerChart extends ChartWidget
{
    protected ?string $heading = '5 Produk Gelang Terlaris (Unit Terjual)';
    protected static ?int $sort = 3;

    protected function getData(): array
    {
        // Hitung total kuantitas per produk dari pesanan selesai
        $bestSellers = OrderItem::select('product_id', DB::raw('SUM(quantity) as total_qty'))
            ->whereHas('order', function ($query) {
                $query->where('status', 'selesai');
            })
            ->groupBy('product_id')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->with('product')
            ->get();

        $labels = [];
        $data = [];

        foreach ($bestSellers as $item) {
            $labels[] = $item->product?->name ?? 'Produk';
            $data[] = (int) $item->total_qty;
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
