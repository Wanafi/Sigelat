<?php

namespace App\Filament\Resources\Manajemen\GelarResource\Pages;

use App\Filament\Resources\Manajemen\GelarResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewGelar extends ViewRecord
{
    protected static string $resource = GelarResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
