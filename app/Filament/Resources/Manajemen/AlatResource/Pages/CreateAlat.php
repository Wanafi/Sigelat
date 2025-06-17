<?php

namespace App\Filament\Resources\Manajemen\AlatResource\Pages;

use App\Filament\Resources\Manajemen\AlatResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateAlat extends CreateRecord
{
    protected static string $resource = AlatResource::class;
    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Alat Telah Terdaftar';
    }
}
