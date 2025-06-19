<?php

namespace Database\Seeders;

use App\Models\Mobil;
use App\Models\Alat;
use App\Models\Gelar;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class InventorySeeder extends Seeder
{
    public function run(): void
    {
        $faker = \Faker\Factory::create('id_ID');

        // 1. Seed Mobil (maksimal 13 data)
        $mobilList = [];
        for ($i = 1; $i <= 13; $i++) {
            $mobil = Mobil::create([
                'nomor_plat' => 'DA ' . rand(1000, 9999) . ' XX',
                'nama_tim' => $faker->randomElement(['Ops', 'Har', 'Assessment', 'Raw']),
                'merk_mobil' => $faker->randomElement(['Hilux', 'Innova', 'Carry']),
                'no_unit' => $faker->randomElement(['Unit12', 'Unit13', 'Unit14']),
                'status_mobil' => $faker->randomElement(['Aktif', 'Tidak Aktif', 'DalamPerbaikan']),
            ]);
            $mobilList[] = $mobil->id;
        }

        // 2. Seed Alat (254 data)
        $alatIDs = [];
        for ($i = 1; $i <= 254; $i++) {
            $alat = Alat::create([
                'kode_barcode' => 'ALAT-' . strtoupper(Str::random(8)),
                'nama_alat' => ucfirst($faker->words(2, true)),
                'kategori_alat' => $faker->randomElement(['Pengukuran', 'Keamanan', 'Listrik', 'Instalasi']),
                'merek_alat' => $faker->randomElement(['Fluke', 'Kyoritsu', 'Hioki', 'UNI-T']),
                'spesifikasi' => $faker->sentence(6),
                'tanggal_pembelian' => $faker->dateTimeBetween('-5 years', 'now')->format('Y-m-d'),
                'status_alat' => 'Bagus',
            ]);
            $alatIDs[] = $alat->id;
        }

        // 3. Pastikan ada user
        $userIDs = User::pluck('id')->toArray();
        if (empty($userIDs)) {
            $user = User::factory()->create();
            $userIDs[] = $user->id;
        }

        // 4. Seed Gelar dan relasi
        for ($i = 1; $i <= 35; $i++) {
            $mobilId = $faker->randomElement($mobilList);
            $gelar = Gelar::create([
                'mobil_id' => $mobilId,
                'status' => 'Lengkap', // default
                'tanggal_cek' => $faker->dateTimeBetween('-1 month', 'now')->format('Y-m-d'),
            ]);

            $alatDipakai = collect($alatIDs)->random(rand(3, 7));
            $statusGelar = 'Lengkap';

            foreach ($alatDipakai as $alatId) {
                $kondisi = $faker->randomElement(['Bagus', 'Rusak', 'Hilang']);

                // Insert ke detail_alats
                DB::table('detail_alats')->updateOrInsert([
                    'mobil_id' => $mobilId,
                    'alat_id' => $alatId,
                ], [
                    'kondisi' => $kondisi,
                    'keterangan' => $faker->sentence(3),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Insert ke detail_gelars
                DB::table('detail_gelars')->insert([
                    'gelar_id' => $gelar->id,
                    'alat_id' => $alatId,
                    'status_alat' => $kondisi,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                Alat::where('id', $alatId)->update(['status_alat' => $kondisi]);

                if ($kondisi === 'Hilang') {
                    $statusGelar = 'Tidak Lengkap';
                }
            }

            $gelar->update(['status' => $statusGelar]);

            // Relasi pelaksana
            $pelaksana = collect($userIDs)->random(min(count($userIDs), rand(1, 3)))->values();
            foreach ($pelaksana as $userId) {
                DB::table('pelaksanas')->insert([
                    'gelar_id' => $gelar->id,
                    'user_id' => $userId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
