<?php

namespace App\Filament\Resources\LaporanAlatResource\Pages;

use App\Filament\Resources\LaporanAlatResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewLaporanAlat extends ViewRecord
{
    protected static string $resource = LaporanAlatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
