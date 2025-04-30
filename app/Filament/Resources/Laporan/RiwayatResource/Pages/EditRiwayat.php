<?php

namespace App\Filament\Resources\Laporan\RiwayatResource\Pages;

use App\Filament\Resources\Laporan\RiwayatResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRiwayat extends EditRecord
{
    protected static string $resource = RiwayatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
