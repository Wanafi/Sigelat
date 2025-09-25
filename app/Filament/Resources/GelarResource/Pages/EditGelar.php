<?php

namespace App\Filament\Resources\GelarResource\Pages;

use App\Filament\Resources\GelarResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGelar extends EditRecord
{
    protected static string $resource = GelarResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
