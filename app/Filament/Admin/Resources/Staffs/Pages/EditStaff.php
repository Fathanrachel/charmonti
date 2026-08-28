<?php

namespace App\Filament\Admin\Resources\Staffs\Pages;

use App\Filament\Admin\Resources\Staffs\StaffResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditStaff extends EditRecord
{
    protected static string $resource = StaffResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    public function getTitle(): string
    {
        return 'Edit Staff: ' . ($this->record->profile?->name ?? $this->record->email);
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['profile'] = [
            'name' => $this->record->profile?->name,
            'phone' => $this->record->profile?->phone,
            'role' => $this->record->profile?->role ?? 'kasir',
        ];

        return $data;
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $profileData = $data['profile'] ?? [];
        unset($data['profile']);

        $record->update($data);

        if ($record->profile) {
            $record->profile->update([
                'name' => $profileData['name'] ?? $record->profile->name,
                'phone' => $profileData['phone'] ?? $record->profile->phone,
                'role' => $profileData['role'] ?? $record->profile->role,
            ]);
        } else {
            $record->profile()->create([
                'name' => $profileData['name'] ?? null,
                'phone' => $profileData['phone'] ?? null,
                'role' => $profileData['role'] ?? 'kasir',
            ]);
        }

        return $record;
    }
}
