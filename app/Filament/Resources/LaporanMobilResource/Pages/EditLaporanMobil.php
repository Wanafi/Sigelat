<?php

namespace App\Filament\Resources\LaporanMobilResource\Pages;

use App\Filament\Resources\LaporanMobilResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLaporanMobil extends EditRecord
{
    protected static string $resource = LaporanMobilResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
