<?php

namespace App\Filament\Resources\AlatResource\Pages;

use App\Filament\Resources\AlatResource;
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
