<?php

namespace App\Filament\Resources\LaporanGelarResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ListRecords\Tab;
use App\Filament\Resources\LaporanGelarResource;

class ListLaporanGelars extends ListRecords
{
    protected static string $resource = LaporanGelarResource::class;

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
            'Proses' => Tab::make()
                ->modifyQueryUsing(fn($query) => $query->where('status', 'proses')),
            ];
    }
}
