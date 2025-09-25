<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Mobil;

class MobilSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Mobil::create([
            'id' => 1,
            'nomor_plat' => 'DA 1741 NA',
            'merk_mobil' => 'Innova',
            'no_seri' => 'Yantek',
            'no_unit' => 'Unit 17',
            'nama_tim' => 'Ops',
            'status_mobil' => 'Aktif',
            'created_at' => '2025-08-21 04:07:28',
            'updated_at' => '2025-08-21 04:07:28',
        ]);
    }
}