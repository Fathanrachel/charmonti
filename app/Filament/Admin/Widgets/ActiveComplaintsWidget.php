<?php

namespace App\Filament\Admin\Widgets;

use App\Filament\Admin\Resources\Complaints\ComplaintResource;
use App\Models\Complaint;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ActiveComplaintsWidget extends BaseWidget
{
    protected static ?int $sort = 3;
    protected int | string | array $columnSpan = 4;

    protected function getStats(): array
    {
        $activeComplaints = Complaint::whereIn('status', ['open', 'diproses'])->count();

        return [
            Stat::make('Keluhan Aktif (Komplain)', $activeComplaints . ' Kasus')
                ->description('Keluhan pelanggan yang belum selesai ditindaklanjuti')
                ->descriptionIcon('heroicon-m-chat-bubble-left-right')
                ->color($activeComplaints > 0 ? 'danger' : 'success')
                ->url(ComplaintResource::getUrl('index')),
        ];
    }
}
