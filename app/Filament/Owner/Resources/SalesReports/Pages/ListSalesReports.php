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
            CreateAction::make(),
        ];
    }
}
