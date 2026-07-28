<?php

namespace App\Filament\Admin\Resources\Staffs\Pages;

use App\Filament\Admin\Resources\Staffs\StaffResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateStaff extends CreateRecord
{
    protected static string $resource = StaffResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function handleRecordCreation(array $data): Model
    {
        $profileData = $data['profile'] ?? [];
        unset($data['profile']);

        $user = static::getModel()::create($data);

        $user->profile()->create([
            'name' => $profileData['name'] ?? null,
            'phone' => $profileData['phone'] ?? null,
            'role' => $profileData['role'] ?? 'kasir',
        ]);

        return $user;
    }
}
