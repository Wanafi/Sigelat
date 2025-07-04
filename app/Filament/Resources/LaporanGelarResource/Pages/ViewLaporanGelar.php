<?php

namespace App\Filament\Resources\LaporanGelarResource\Pages;

use App\Models\Gelar;
use Filament\Resources\Pages\Page;
use App\Filament\Resources\LaporanGelarResource;

class ViewLaporanGelar extends Page
{
    protected static string $resource = LaporanGelarResource::class;

    protected static string $view = 'filament.resources.gelar-resource.pages.formulir';

    public $record;

    public function mount($record): void
    {
        $this->record = Gelar::with(['mobil.alats', 'detailGelars.alat'])->findOrFail($record);
    }

    protected function getViewData(): array
    {
        return [
            'gelar' => $this->record,
        ];
    }
}
