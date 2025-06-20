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

        // 1. Seed Mobil (maks 13)
        $mobilList = [];
        for ($i = 1; $i <= 13; $i++) {
            $mobil = Mobil::create([
                'nomor_plat' => 'DA ' . rand(1000, 9999) . ' XX',
                'nama_tim' => $faker->randomElement(['Ops', 'Har', 'Assessment', 'Raw']),
                'merk_mobil' => $faker->randomElement(['Hilux', 'Innova', 'Carry']),
                'no_unit' => $faker->randomElement(['Unit12', 'Unit13', 'Unit14']),
                'status_mobil' => $faker->randomElement(['Aktif', 'Tidak Aktif', 'Dalam Perbaikan']),
            ]);
            $mobilList[] = $mobil->id;
        }

        // 2. Seed User (jika belum ada)
        $userIDs = User::pluck('id')->toArray();
        if (empty($userIDs)) {
            $user = User::factory()->create();
            $userIDs[] = $user->id;
        }

        // 3. Seed Alat (254), langsung ditugaskan ke mobil
        $alatIDs = [];
        for ($i = 1; $i <= 254; $i++) {
            $alat = Alat::create([
                'kode_barcode' => 'QR-' . strtoupper(Str::random(8)),
                'nama_alat' => ucfirst($faker->words(2, true)),
                'kategori_alat' => $faker->randomElement(['distribusi','pemeliharaan','proteksi','pengukuran','energi_terbarukan','pendukung']),
                'merek_alat' => $faker->randomElement(['Fluke', 'Kyoritsu', 'Hioki', 'UNI-T']),
                'spesifikasi' => $faker->sentence(6),
                'tanggal_pembelian' => $faker->dateTimeBetween('-5 years', 'now')->format('Y-m-d'),
                'status_alat' => 'Bagus',
                'mobil_id' => $faker->randomElement($mobilList), // ← langsung dipasang ke mobil
            ]);
            $alatIDs[] = $alat->id;
        }

        // 4. Seed Gelar + relasinya
        for ($i = 1; $i <= 35; $i++) {
            $mobilId = $faker->randomElement($mobilList);
            $userId = $faker->randomElement($userIDs);

            $gelar = Gelar::create([
                'mobil_id' => $mobilId,
                'user_id' => $userId,
                'status' => 'Lengkap',
                'tanggal_cek' => $faker->dateTimeBetween('-1 month', 'now')->format('Y-m-d'),
            ]);

            $alatDipakai = collect($alatIDs)->random(rand(3, 7));
            $statusGelar = 'Lengkap';

            foreach ($alatDipakai as $alatId) {
                $kondisi = $faker->randomElement(['Bagus', 'Rusak', 'Hilang']);

                // detail_gelars
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

            // pelaksanas
            $pelaksana = collect($userIDs)->random(rand(1, min(3, count($userIDs))));
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
