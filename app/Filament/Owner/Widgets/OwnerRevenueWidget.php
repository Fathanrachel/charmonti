<?php

namespace App\Filament\Owner\Widgets;

use App\Filament\Owner\Resources\FinancialReports\FinancialReportResource;
use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class OwnerRevenueWidget extends BaseWidget
{
    protected static ?int $sort = 1;
    protected int | string | array $columnSpan = 4;

    protected function getStats(): array
    {
        $totalRevenue = Order::where('status', 'selesai')->sum('total_price');

        return [
            Stat::make('Total Omzet Penjualan', 'Rp ' . number_format($totalRevenue, 0, ',', '.'))
                ->description('Total omzet dari pesanan selesai')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success')
                ->url(FinancialReportResource::getUrl('index')),
        ];
    }
}
