<?php

namespace App\Filament\Resources\LaporanAlatResource\Pages;

use App\Filament\Resources\LaporanAlatResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLaporanAlat extends EditRecord
{
    protected static string $resource = LaporanAlatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
