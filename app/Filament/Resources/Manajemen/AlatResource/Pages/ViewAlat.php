<?php

namespace App\Filament\Resources\Manajemen\AlatResource\Pages;

use App\Filament\Resources\Manajemen\AlatResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewAlat extends ViewRecord
{
    protected static string $resource = AlatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
