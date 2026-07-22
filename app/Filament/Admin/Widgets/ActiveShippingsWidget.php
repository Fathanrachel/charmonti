<?php

namespace App\Filament\Admin\Widgets;

use App\Filament\Admin\Resources\Shippings\ShippingResource;
use App\Models\Shipping;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ActiveShippingsWidget extends BaseWidget
{
    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = 4;

    protected function getStats(): array
    {
        $activeShippings = Shipping::where('status', 'dikirim')->count();

        return [
            Stat::make('Pengiriman Aktif', $activeShippings . ' Paket')
                ->description('Paket pesanan sedang dikirim oleh kurir di jalan')
                ->descriptionIcon('heroicon-m-truck')
                ->color('info')
                ->url(ShippingResource::getUrl('index')),
        ];
    }
}
