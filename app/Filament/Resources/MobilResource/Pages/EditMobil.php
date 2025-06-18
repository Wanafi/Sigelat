<?php

namespace App\Filament\Resources\Manajemen\MobilResource\Pages;

use App\Filament\Resources\Manajemen\MobilResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMobil extends EditRecord
{
    protected static string $resource = MobilResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
