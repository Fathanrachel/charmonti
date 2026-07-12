<?php

namespace App\Filament\Owner\Widgets;

use App\Models\OrderItem;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class BestSellerChart extends ChartWidget
{
    protected ?string $heading = 'Performa Penjualan per Kategori (Unit Terjual)';
    protected static ?int $sort = 6;
    protected int | string | array $columnSpan = 6;

    protected function getData(): array
    {
        // 1. Ambil semua order items dari pesanan yang sukses/selesai
        $orderItems = OrderItem::whereHas('order', function ($query) {
                $query->where('status', 'selesai');
            })
            ->with('product')
            ->get();

        // 2. Kelompokkan ke dalam kategori bisnis
        $categorySales = [
            'Gelang Jadi' => 0,
            'Gelang Custom' => 0,
            'Cincin' => 0,
        ];

        foreach ($orderItems as $item) {
            $product = $item->product;
            if (!$product) continue;

            if ($product->product_name === 'Gelang Custom') {
                $categorySales['Gelang Custom'] += (int) $item->qty;
            } elseif ($product->category === 'gelang_jadi') {
                $categorySales['Gelang Jadi'] += (int) $item->qty;
            } elseif ($product->category === 'cincin') {
                $categorySales['Cincin'] += (int) $item->qty;
            }
        }

        // 3. Format data untuk diagram batang
        $labels = [];
        $data = [];
        $colors = [];

        // Definisikan warna yang elegan untuk masing-masing kategori
        $colorPalette = [
            'Gelang Jadi' => '#fbbf24',    // Amber-400 (Kuning Emas)
            'Gelang Custom' => '#ec4899',  // Pink-500 (Pink Soft Khas Charmonti)
            'Cincin' => '#f97316',         // Orange-500
        ];

        foreach ($categorySales as $catName => $qty) {
            $labels[] = $catName;
            $data[] = $qty;
            $colors[] = $colorPalette[$catName];
        }

        return [
            'datasets' => [
                [
                    'label' => 'Total Unit Terjual',
                    'data' => $data,
                    'backgroundColor' => $colors,
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
