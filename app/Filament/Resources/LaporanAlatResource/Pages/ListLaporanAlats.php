<?php

namespace App\Filament\Resources\LaporanAlatResource\Pages;

use App\Models\Alat;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ListRecords\Tab;
use App\Filament\Resources\LaporanAlatResource;

class ListLaporanAlats extends ListRecords
{
    protected static string $resource = LaporanAlatResource::class;

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

            'Hilang' => Tab::make()
                ->modifyQueryUsing(fn($query) => $query->where('status_alat', 'hilang'))
                ->badge(Alat::query()->where('status_alat', 'hilang')->count()),
            'Rusak' => Tab::make()
                ->modifyQueryUsing(fn($query) => $query->where('status_alat', 'rusak'))
                ->badge(Alat::query()->where('status_alat', 'rusak')->count()),
        ];
    }
}
