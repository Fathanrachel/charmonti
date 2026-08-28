<?php

namespace App\Filament\Owner\Resources\FinancialReports\Pages;

use App\Filament\Owner\Resources\FinancialReports\FinancialReportResource;
use Filament\Resources\Pages\CreateRecord;

class CreateFinancialReport extends CreateRecord
{
    protected static string $resource = FinancialReportResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
