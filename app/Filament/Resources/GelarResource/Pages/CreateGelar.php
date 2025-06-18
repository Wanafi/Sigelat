<?php

namespace App\Filament\Resources\Manajemen\GelarResource\Pages;

use App\Models\Alat;
use App\Models\Gelar;
use Filament\Actions;
use Illuminate\Support\Facades\Auth;
use App\Filament\Resources\Manajemen\GelarResource;
use Filament\Resources\Pages\CreateRecord;

class CreateGelar extends CreateRecord
{
    protected static string $resource = GelarResource::class;

    // Metode untuk memodifikasi data sebelum disimpan
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = Auth::id(); // Ambil ID user yang sedang login
        if (isset($data['alat_ids'])) {
            $alatDipilih = is_array($data['alat_ids']) ? count($data['alat_ids']) : count(json_decode($data['alat_ids'], true));
            $totalAlat = Alat::count();
            $data['status'] = ($alatDipilih === $totalAlat) ? 'Lengkap' : 'Tidak Lengkap';
    
            // Jika perlu encode JSON alat_ids
            $data['alat_ids'] = json_encode($data['alat_ids']);
        }
        return $data;
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Kegiatan Telah Tercatat';
    }

}
