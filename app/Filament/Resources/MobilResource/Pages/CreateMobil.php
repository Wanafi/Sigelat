<?php

namespace App\Filament\Resources\MobilResource\Pages;

use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use App\Filament\Resources\MobilResource;
use Filament\Resources\Pages\CreateRecord;

class CreateMobil extends CreateRecord
{
    protected static string $resource = MobilResource::class;


    // protected function getFormActions(): array
    // {
    //     return [
    //         CreateAction::make()
    //             ->label('Simpan')
    //             ->color('primary'),

    //         Action::make('cancel')
    //             ->label('Batalkan')
    //             ->url($this->getResource()::getUrl()) // redirect ke halaman index
    //             ->color('gray'),
    //     ];
    // }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Mobil Telah Terdaftar';
    }

    public function getTitle(): string
    {
        return 'Buat Data Mobil';
    }
}
