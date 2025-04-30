<?php

namespace App\Filament\Resources\Manajemen\GelarResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ListRecords\Tab;
use App\Filament\Resources\Manajemen\GelarResource;

class ListGelars extends ListRecords
{
    protected static string $resource = GelarResource::class;

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
            'Lengkap' => Tab::make()
                ->modifyQueryUsing(fn($query) => $query->where('status', 'lengkap')),
            'Tidak Lengkap' => Tab::make()
                ->modifyQueryUsing(fn($query) => $query->where('status', 'tidak lengkap')),
            ];
    }
}
