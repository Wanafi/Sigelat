<?php

namespace App\Filament\Resources\GelarResource\Pages;

use App\Models\Alat;
use App\Models\Gelar;
use App\Models\Pelaksana;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Illuminate\Support\Facades\DB;
use App\Filament\Resources\GelarResource;
use Filament\Resources\Pages\CreateRecord;

class CreateGelar extends CreateRecord
{
    protected static string $resource = GelarResource::class;
        public function getTitle(): string
    {
        return 'Buat Data Kegiatan Gelar Alat';
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

    protected function handleRecordCreation(array $data): Gelar
    {
        // Buat data utama gelar
        $gelar = Gelar::create($data);

        // 🧪 Debug untuk memastikan data masuk
        // dd($data);

        // Simpan Pelaksana
        foreach ($data['pelaksana_ids'] ?? [] as $userId) {
            Pelaksana::create([
                'gelar_id' => $gelar->id,
                'user_id' => $userId,
            ]);
        }

        // Simpan Detail Alat & update status_alat
        $statusGelar = 'Lengkap';
        foreach ($data['detail_alats'] ?? [] as $alat) {
            if (!isset($alat['alat_id'], $alat['kondisi'])) continue;

            DB::table('detail_gelars')->insert([
                'gelar_id' => $gelar->id,
                'alat_id' => $alat['alat_id'],
                'status_alat' => $alat['kondisi'],
                'keterangan' => $alat['keterangan'] ?? null,
                'foto_kondisi' => $alat['foto_kondisi'] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);


            Alat::where('id', $alat['alat_id'])->update([
                'status_alat' => $alat['kondisi'],
            ]);

            if ($alat['kondisi'] === 'Hilang') {
                $statusGelar = 'Tidak Lengkap';
            }
        }

        // Update status gelar berdasarkan alat
        $gelar->update(['status' => $statusGelar]);

        return $gelar;
    }
}
