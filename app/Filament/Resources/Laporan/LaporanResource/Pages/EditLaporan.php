<?php

namespace App\Filament\Resources\Laporan\LaporanResource\Pages;

use App\Filament\Resources\Laporan\LaporanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLaporan extends EditRecord
{
    protected static string $resource = LaporanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
