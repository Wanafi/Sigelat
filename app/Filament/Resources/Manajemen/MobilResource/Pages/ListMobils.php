<?php

namespace App\Filament\Resources\Manajemen\MobilResource\Pages;

use Filament\Actions;
use App\Filament\Resources\Manajemen\MobilResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ListRecords\Tab;

class ListMobils extends ListRecords
{
    protected static string $resource = MobilResource::class;
    public function getTabs(): array
    {
        return [
            'Semua' => Tab::make(),
            'Aktif' => Tab::make()
                ->modifyQueryUsing(fn($query) => $query->where('status_mobil', 'aktif')),
            'Tidak Aktif' => Tab::make()
                ->modifyQueryUsing(fn($query) => $query->where('status_mobil', 'tidakaktif')),
            'Dalam Perbaikan' => Tab::make()
                ->modifyQueryUsing(fn($query) => $query->where('status_mobil', 'DalamPerbaikan')),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
