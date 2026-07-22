<?php

namespace App\Filament\Admin\Widgets;

use App\Filament\Admin\Resources\Orders\OrderResource;
use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class OrdersToProcessWidget extends BaseWidget
{
    protected static ?int $sort = 1;
    protected int | string | array $columnSpan = 4;

    protected function getStats(): array
    {
        $ordersToProcess = Order::where('status', 'diproses')->count();

        return [
            Stat::make('Pesanan Perlu Diproses', $ordersToProcess . ' Transaksi')
                ->description('Pesanan lunas baru yang harus dikonfirmasi & dikirim')
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color($ordersToProcess > 0 ? 'warning' : 'gray')
                ->url(OrderResource::getUrl('index')),
        ];
    }
}
