<?php

namespace App\Filament\Resources\Manajemen\AlatResource\Pages;

use Filament\Actions;
use App\Filament\Resources\Manajemen\AlatResource;
use App\Models\Alat;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ListRecords\Tab;

class ListAlats extends ListRecords
{
    protected static string $resource = AlatResource::class;
    public function getTabs(): array
    {
        return [
            'Semua' => Tab::make(),
            'Bagus' => Tab::make()
                ->modifyQueryUsing(fn($query) => $query->where('status_alat', 'Bagus'))
                ->badge(Alat::query()->where('status_alat', 'Bagus')->count()),
            'Hilang' => Tab::make()
                ->modifyQueryUsing(fn($query) => $query->where('status_alat', 'hilang'))
                ->badge(Alat::query()->where('status_alat', 'hilang')->count()),
            'Rusak' => Tab::make()
                ->modifyQueryUsing(fn($query) => $query->where('status_alat', 'rusak'))
                ->badge(Alat::query()->where('status_alat', 'rusak')->count()),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
