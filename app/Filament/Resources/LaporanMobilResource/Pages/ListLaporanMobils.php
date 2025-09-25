<?php

namespace App\Filament\Resources\LaporanMobilResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ListRecords\Tab;
use App\Filament\Resources\LaporanMobilResource;
use App\Models\Mobil;

class ListLaporanMobils extends ListRecords
{
    protected static string $resource = LaporanMobilResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'Semua' => Tab::make(),

            'Tidak Aktif' => Tab::make()
                ->modifyQueryUsing(fn($query) => $query->where('status_mobil', 'Tidak Aktif'))
                ->badge(fn () => Mobil::where('status_mobil', 'Tidak Aktif')->count()),

            'Dalam Perbaikan' => Tab::make()
                ->modifyQueryUsing(fn($query) => $query->where('status_mobil', 'Dalam Perbaikan'))
                ->badge(fn () => Mobil::where('status_mobil', 'Dalam Perbaikan')->count()),
        ];
    }
}
