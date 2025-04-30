<?php

namespace App\Filament\Resources\LaporanGelarResource\Pages;

use App\Filament\Resources\LaporanGelarResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewLaporanGelar extends ViewRecord
{
    protected static string $resource = LaporanGelarResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
