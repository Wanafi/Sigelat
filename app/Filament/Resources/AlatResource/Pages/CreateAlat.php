<?php

namespace App\Filament\Resources\AlatResource\Pages;

use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use App\Filament\Resources\AlatResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateAlat extends CreateRecord
{
    protected static string $resource = AlatResource::class;
    public function getTitle(): string
    {
        return 'Buat Data Alat';
    }
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
        return 'Alat Telah Terdaftar';
    }
}
