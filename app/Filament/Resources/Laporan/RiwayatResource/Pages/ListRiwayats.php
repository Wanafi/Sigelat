<?php

namespace App\Filament\Resources\Laporan\RiwayatResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Infolists\Components\Tabs\Tab;
use App\Filament\Resources\Laporan\RiwayatResource;
use App\Models\Riwayat;

class ListRiwayats extends ListRecords
{
    protected static string $resource = RiwayatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
