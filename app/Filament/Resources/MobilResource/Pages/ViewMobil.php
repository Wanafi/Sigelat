<?php

namespace App\Filament\Resources\MobilResource\Pages;

use App\Filament\Resources\MobilResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewMobil extends ViewRecord
{
    protected static string $resource = MobilResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
