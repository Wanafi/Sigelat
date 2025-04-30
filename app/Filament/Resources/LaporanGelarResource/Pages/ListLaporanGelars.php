<?php

namespace App\Filament\Resources\LaporanGelarResource\Pages;

use App\Filament\Resources\LaporanGelarResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLaporanGelars extends ListRecords
{
    protected static string $resource = LaporanGelarResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
