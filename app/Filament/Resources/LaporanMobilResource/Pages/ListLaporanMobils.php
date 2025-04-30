<?php

namespace App\Filament\Resources\LaporanMobilResource\Pages;

use App\Filament\Resources\LaporanMobilResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLaporanMobils extends ListRecords
{
    protected static string $resource = LaporanMobilResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
