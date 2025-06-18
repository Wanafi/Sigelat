<?php

namespace App\Filament\Resources\Manajemen\MobilResource\Pages;

use App\Filament\Resources\Manajemen\MobilResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateMobil extends CreateRecord
{
    protected static string $resource = MobilResource::class;
    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Mobil Telah Terdaftar';
    }
}
