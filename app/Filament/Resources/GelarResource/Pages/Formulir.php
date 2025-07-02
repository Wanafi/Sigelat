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

    public function getHeaderActions(): array
{
    return [
        Action::make('print')
            ->label('Cetak')
            ->icon('heroicon-o-printer')
            ->action(fn () => $this->dispatch('triggerPrint')),

        // Action::make('download')
        //     ->label('Download PDF')
        //     ->icon('heroicon-o-arrow-down-tray')
        //     ->url(route('gelar.formulir.download', ['id' => $this->gelar->id]))
        //     ->openUrlInNewTab(),
    ];
}
    public function mount($record): void
    {
        $this->gelar = Gelar::with(['mobil.alats', 'detailGelars.alat'])->findOrFail($record);
    }

    protected function getViewData(): array
    {
        return [
            'gelar' => $this->gelar,
        ];
    }
}
