<?php

namespace App\Filament\Owner\Widgets;

use App\Models\Order;
use App\Models\Review;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class OwnerOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        // 1. Total Omzet (Semua pesanan yang berstatus selesai)
        $totalRevenue = Order::where('status', 'selesai')->sum('total_price');

        // 2. Total Pesanan Selesai
        $completedOrdersCount = Order::where('status', 'selesai')->count();

        // 3. Rata-rata Rating Ulasan
        $averageRating = Review::avg('rating') ?? 0;

        return [
            Stat::make('Total Omzet Penjualan', 'Rp ' . number_format($totalRevenue, 0, ',', '.'))
                ->description('Total omzet dari pesanan selesai')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),

            Stat::make('Pesanan Selesai', $completedOrdersCount . ' Transaksi')
                ->description('Jumlah pesanan berhasil dikirim')
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color('info'),

            Stat::make('Rata-rata Kepuasan', number_format($averageRating, 1) . ' / 5.0')
                ->description('Berdasarkan ulasan pembeli')
                ->descriptionIcon('heroicon-m-star')
                ->color('warning'),
        ];
    }
}
