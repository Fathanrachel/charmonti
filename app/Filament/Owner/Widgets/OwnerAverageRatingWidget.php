<?php

namespace App\Filament\Owner\Widgets;

use App\Models\Review;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class OwnerAverageRatingWidget extends BaseWidget
{
    protected static ?int $sort = 3;
    protected int | string | array $columnSpan = 4;

    protected function getStats(): array
    {
        $averageRating = Review::avg('rating') ?? 0;

        return [
            Stat::make('Rata-rata Kepuasan', number_format($averageRating, 1) . ' / 5.0')
                ->description('Berdasarkan ulasan pembeli')
                ->descriptionIcon('heroicon-m-star')
                ->color('warning'),
        ];
    }
}
