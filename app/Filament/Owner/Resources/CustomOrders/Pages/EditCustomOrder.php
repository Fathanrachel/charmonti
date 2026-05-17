<?php

namespace App\Filament\Owner\Resources\CustomOrders\Pages;

use App\Filament\Owner\Resources\CustomOrders\CustomOrderResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCustomOrder extends EditRecord
{
    protected static string $resource = CustomOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
