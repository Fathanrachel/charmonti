<?php

namespace App\Filament\Owner\Resources\FinancialReports\Pages;

use App\Filament\Owner\Resources\FinancialReports\FinancialReportResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListFinancialReports extends ListRecords
{
    protected static string $resource = FinancialReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('downloadPdf')
                ->label('Unduh PDF Laporan')
                ->icon('heroicon-o-document-arrow-down')
                ->color('danger')
                ->url(fn () => route('owner.reports.financial.pdf', ['period' => request()->input('tableFilters.period.value', 'daily')]))
                ->openUrlInNewTab(),
        ];
    }
}
