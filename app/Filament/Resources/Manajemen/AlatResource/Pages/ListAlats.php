<?php

namespace App\Filament\Resources\Manajemen\AlatResource\Pages;

use Filament\Actions;
use App\Filament\Resources\Manajemen\AlatResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ListRecords\Tab;

class ListAlats extends ListRecords
{
    protected static string $resource = AlatResource::class;
    public function getTabs(): array
    {
        return [
            'Semua' => Tab::make(),
            'Dipinjam' => Tab::make()
                ->modifyQueryUsing(fn($query) => $query->where('status_alat', 'dipinjam')),
            'Habis' => Tab::make()
                ->modifyQueryUsing(fn($query) => $query->where('status_alat', 'habis')),
            'Rusak' => Tab::make()
                ->modifyQueryUsing(fn($query) => $query->where('status_alat', 'rusak')),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
