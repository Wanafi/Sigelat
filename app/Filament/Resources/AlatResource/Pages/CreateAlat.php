<?php

namespace App\Filament\Resources\AlatResource\Pages;

use Filament\Actions;
use App\Filament\Resources\AlatResource;
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
