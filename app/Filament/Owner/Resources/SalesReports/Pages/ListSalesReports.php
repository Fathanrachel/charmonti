<?php

namespace App\Filament\Owner\Resources\SalesReports\Pages;

use App\Filament\Owner\Resources\SalesReports\SalesReportResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSalesReports extends ListRecords
{
    protected static string $resource = SalesReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('downloadPdf')
                ->label('Unduh PDF Laporan')
                ->icon('heroicon-o-document-arrow-down')
                ->color('danger')
                ->action(function ($livewire) {
                    $period = data_get($livewire->tableFilters, 'period.value', 'daily');
                    return redirect()->route('owner.reports.sales.pdf', ['period' => $period]);
                }),
        ];
    }
}
