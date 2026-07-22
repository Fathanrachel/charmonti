<?php

namespace App\Filament\Owner\Widgets;

use App\Filament\Owner\Resources\Orders\OrderResource;
use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class OwnerCompletedOrdersWidget extends BaseWidget
{
    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = 4;

    protected function getStats(): array
    {
        $completedOrdersCount = Order::where('status', 'selesai')->count();

        return [
            Stat::make('Pesanan Selesai', $completedOrdersCount . ' Transaksi')
                ->description('Jumlah pesanan berhasil dikirim')
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color('info')
                ->url(OrderResource::getUrl('index')),
        ];
    }
}
