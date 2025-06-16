<?php

namespace Database\Seeders;

use App\Models\Mobil;
use App\Models\Alat;
use App\Models\Gelar;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class InventorySeeder extends Seeder
{
    public function run(): void
    {
        $faker = \Faker\Factory::create('id_ID');

        // 1. Seed Mobil (13 data)
        $mobilList = [];
        for ($i = 1; $i <= 13; $i++) {
            $mobil = Mobil::create([
                'nomor_plat' => 'DA ' . rand(1000, 9999) . ' XX',
                'merk_mobil' => $faker->randomElement(['Hilux', 'Innova', 'Carry']),
                'no_unit' => $faker->randomElement(['Unit12', 'Unit13', 'Unit14']),
                'status_mobil' => $faker->randomElement(['Aktif', 'TidakAktif', 'DalamPerbaikan']),
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
                'status_alat' => $faker->randomElement(['Dipinjam', 'Rusak', 'Habis']),
                'mobil_id' => $faker->randomElement($mobilList),
            ]);
            $alatIDs[] = $alat->id;
        }

        // Pastikan ada user
        $userIDs = User::pluck('id')->toArray();
        if (empty($userIDs)) {
            $user = \App\Models\User::factory()->create(); // pastikan ada factory
            $userIDs[] = $user->id;
        }

        // 3. Seed Gelar (35 data)
        for ($i = 1; $i <= 35; $i++) {
            $randomAlatIds = collect($alatIDs)->random(rand(3, 7))->values()->all();

            Gelar::create([
                'mobil_id' => $faker->randomElement($mobilList),
                'user_id' => $faker->randomElement($userIDs),
                'alat_ids' => json_encode($randomAlatIds),
                'status' => $faker->randomElement(['Lengkap', 'TidakLengkap', 'Proses']),
                'tanggal_cek' => $faker->dateTimeBetween('-1 month', 'now')->format('Y-m-d'),
            ]);
        }
    }
}
