<?php

namespace Database\Seeders;

use App\Models\Alat;
use App\Models\User;
use App\Models\Gelar;
use App\Models\Mobil;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class InventorySeeder extends Seeder
{
    public function run(): void
    {
        $faker = \Faker\Factory::create('id_ID');

        // ✅ Tambah User utama kamu (akun real)
        // $admin = User::updateOrCreate([
        //     'email' => 'admin@gmail.com',
        // ], [
        //     'name' => 'Naufal Najwan Abdurrafi',
        //     'password' => bcrypt('admin123.'),
        // ]);

        // ✅ Buat role super_admin jika belum ada
        // if (!Role::where('name', 'super_admin')->exists()) {
        //     Role::create(['name' => 'super_admin']);
        // }

        // $admin->assignRole('super_admin');

        // $userIds = User::pluck('id')->toArray();

        // ✅ Tambah Mobil
        $mobilIds = [];
        foreach (range(1, 13) as $i) {
            $mobil = Mobil::create([
                'nomor_plat' => 'DA ' . rand(1000, 9999) . ' ' . strtoupper(Str::random(2)),
                'merk_mobil' => $faker->randomElement(['Hilux', 'Innova', 'Carry']),
                'no_seri' => 'SERI' . rand(10000, 20000),
                'no_unit' => 'Unit' . rand(10, 20),
                'nama_tim' => $faker->randomElement(['Ops', 'Har', 'Assessment', 'Raw']),
                'status_mobil' => $faker->randomElement(['Aktif', 'Tidak Aktif', 'Dalam Perbaikan']),
            ]);
            $mobilIds[] = $mobil->id;
        }

        // ✅ Tambah Alat (45 alat per mobil)
        $alatIds = [];
        foreach ($mobilIds as $mobilId) {
            foreach (range(1, 45) as $i) {
                $alat = Alat::create([
                    'mobil_id' => $mobilId,
                    'kode_barcode' => 'QR-' . strtoupper(Str::random(8)),
                    'nama_alat' => ucfirst($faker->words(2, true)),
                    'kategori_alat' => $faker->randomElement(['distribusi', 'pemeliharaan', 'proteksi', 'pengukuran', 'energi_terbarukan', 'pendukung']),
                    'merek_alat' => $faker->randomElement(['Fluke', 'Kyoritsu', 'Hioki', 'UNI-T']),
                    'spesifikasi' => $faker->sentence(5),
                    'tanggal_masuk' => $faker->dateTimeBetween('-3 years', 'now')->format('Y-m-d'),
                    'status_alat' => $faker->randomElement(['Baik', 'Rusak', 'Hilang']),
                    'foto' => 'https://picsum.photos/seed/' . rand(1, 10000) . '/400/400',
                ]);
                $alatIds[] = $alat->id;
            }
        }

        // ✅ Tambah Gelar
        foreach (range(1, 35) as $i) {
            $gelar = Gelar::create([
                'mobil_id' => $faker->randomElement($mobilIds),
                'pelaksana' => $faker->name(),
                'status' => 'Lengkap',
                'tanggal_cek' => $faker->dateTimeBetween('-30 days', 'now')->format('Y-m-d'),
            ]);

            $alatDipakai = collect($alatIds)->random(rand(3, 7));
            $statusGelar = 'Lengkap';

            foreach ($alatDipakai as $alatId) {
                $kondisi = $faker->randomElement(['Baik', 'Rusak', 'Hilang']);

                DB::table('detail_gelars')->insert([
                    'gelar_id' => $gelar->id,
                    'alat_id' => $alatId,
                    'status_alat' => $kondisi,
                    'keterangan' => $faker->randomElement([
                        'Tidak ada masalah',
                        'Perlu pengecekan ulang',
                        'Alat terlihat aus',
                        'Butuh kalibrasi',
                        'Komponen hilang sebagian',
                    ]),
                    'foto_kondisi' => 'https://loremflickr.com/400/400/tools?random=' . rand(1, 10000),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                Alat::where('id', $alatId)->update(['status_alat' => $kondisi]);

                if ($kondisi === 'Hilang') {
                    $statusGelar = 'Tidak Lengkap';
                }
            }

            $gelar->update(['status' => $statusGelar]);
        }
    }
}
