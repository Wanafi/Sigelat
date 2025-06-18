<?php

namespace App\Filament\Resources\Manajemen\AlatResource\Pages;

use App\Filament\Resources\Manajemen\AlatResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAlat extends EditRecord
{
    protected static string $resource = AlatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
