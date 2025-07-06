<?php

namespace App\Filament\Resources\LaporanMobilResource\Pages;

use App\Filament\Resources\LaporanMobilResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewLaporanMobil extends ViewRecord
{
    protected static string $resource = LaporanMobilResource::class;
    public function getTitle(): string
    {
        return 'Lihat Laporan Mobil';
    }
    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
