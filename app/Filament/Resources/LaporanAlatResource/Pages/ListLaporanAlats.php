<?php

namespace App\Filament\Resources\LaporanAlatResource\Pages;

use App\Filament\Resources\LaporanAlatResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLaporanAlats extends ListRecords
{
    protected static string $resource = LaporanAlatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    
}
