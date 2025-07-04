<?php

namespace App\Filament\Resources\GelarResource\Pages;

use App\Models\Gelar;
use Filament\Actions\Action;
use Filament\Resources\Pages\Page;
use App\Filament\Resources\GelarResource;

class Formulir extends Page
{
    protected static string $resource = GelarResource::class;

    protected static string $view = 'filament.resources.gelar-resource.pages.formulir';

    public $gelar;
    public $record;

    public function getHeaderActions(): array
    {
        return [
            Action::make('print')
                ->label('Cetak')
                ->icon('heroicon-o-printer')
                ->action(fn() => $this->dispatch('triggerPrint')),
        ];
    }

    public function mount($record): void
    {
        $this->gelar = Gelar::with([
            'mobil.alats',
            'detailGelars.alat',
            'riwayats.user'
        ])->findOrFail($record);

        $this->record = $this->gelar;
    }

    protected function getViewData(): array
    {
        return [
            'gelar' => $this->gelar,
        ];
    }
}
