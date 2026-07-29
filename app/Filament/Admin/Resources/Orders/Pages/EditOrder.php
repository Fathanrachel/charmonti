<?php

namespace App\Filament\Admin\Resources\Orders\Pages;

use App\Filament\Admin\Resources\Orders\OrderResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditOrder extends EditRecord
{
    protected static string $resource = OrderResource::class;

    public function getTitle(): string|\Illuminate\Contracts\Support\Htmlable
    {
        $customerName = $this->record?->profile?->name ?? 'Pelanggan';
        return "Edit Pesanan #{$this->record->id} — {$customerName}";
    }

    public function getBreadcrumbs(): array
    {
        $resource = static::getResource();
        $customerName = $this->record?->profile?->name ?? 'Pelanggan';

        return [
            $resource::getUrl('index') => 'Orders',
            '#' => "Pesanan #{$this->record->id} ({$customerName})",
            'Edit',
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
