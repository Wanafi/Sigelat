<?php

namespace App\Filament\Resources\Manajemen\MobilResource\Pages;

use App\Models\Mobil;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ListRecords\Tab;
use App\Filament\Resources\Manajemen\MobilResource;

class ListMobils extends ListRecords
{
    protected static string $resource = MobilResource::class;
    public function getTabs(): array
    {
        return [
            'Semua' => Tab::make(),
            'Aktif' => Tab::make()
                ->modifyQueryUsing(fn($query) => $query->where('status_mobil', 'aktif'))
                ->badge(Mobil::query()->where('status_mobil', 'aktif')->count()),
            'Tidak Aktif' => Tab::make()
                ->modifyQueryUsing(fn($query) => $query->where('status_mobil', 'Tidak Aktif'))
                ->badge(Mobil::query()->where('status_mobil', 'Tidak Aktif')->count()),
            'Dalam Perbaikan' => Tab::make()
                ->modifyQueryUsing(fn($query) => $query->where('status_mobil', 'Dalam Perbaikan'))
                ->badge(Mobil::query()->where('status_mobil', 'Dalam Perbaikan')->count()),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
