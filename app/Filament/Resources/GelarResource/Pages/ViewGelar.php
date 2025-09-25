<?php

namespace App\Filament\Resources\GelarResource\Pages;

use App\Filament\Resources\GelarResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewGelar extends ViewRecord
{
    protected static string $resource = GelarResource::class;
    public function getTitle(): string
    {
        return 'Lihat Detail Gelar Alat';
    }
    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
