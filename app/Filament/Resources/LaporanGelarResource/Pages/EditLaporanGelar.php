<?php

namespace App\Filament\Resources\LaporanGelarResource\Pages;

use App\Filament\Resources\LaporanGelarResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLaporanGelar extends EditRecord
{
    protected static string $resource = LaporanGelarResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
