<?php

namespace App\Filament\Resources\UserResource\Pages;

use Filament\Actions;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use App\Filament\Resources\UserResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    public function getTitle(): string
    {
        return 'Buat Data Pengguna';
    }
    protected function getFormActions(): array
    {
        return [
            CreateAction::make()
                ->label('Simpan')
                ->color('primary'),

            Action::make('cancel')
                ->label('Batalkan')
                ->url($this->getResource()::getUrl()) // redirect ke halaman index
                ->color('gray'),
        ];
    }
}
