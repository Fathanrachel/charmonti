<?php

namespace App\Filament\Resources\Shippings\Pages;

use App\Filament\Resources\Shippings\ShippingResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditShipping extends EditRecord
{
    protected static string $resource = ShippingResource::class;

    public function getTitle(): string
    {
        return 'Edit Pengiriman Order #' . ($this->record->order_id ?? $this->record->id);
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
