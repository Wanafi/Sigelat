<?php

namespace App\Filament\Resources\GelarResource\Pages;

use App\Models\Gelar;
use App\Models\Alat;
use App\Models\Pelaksana;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\DB;
use App\Filament\Resources\GelarResource;

class CreateGelar extends CreateRecord
{
    protected static string $resource = GelarResource::class;

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
