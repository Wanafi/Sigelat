<?php

namespace Database\Seeders;

use App\Models\Alat;
use App\Models\Gelar;
use App\Models\Mobil;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InventorySeeder extends Seeder
{
    public function run(): void
    {
        // ✅ Tambah Mobil
        $mobilIds = [];
        $merkMobil = ['Hilux', 'Innova', 'Carry'];
        $namaTim   = ['Ops', 'Har', 'Assessment', 'Raw'];
        $statusMobil = ['Aktif', 'Tidak Aktif', 'Dalam Perbaikan'];

        foreach (range(1, 13) as $i) {
            $mobil = Mobil::create([
                'nomor_plat'   => 'DA ' . rand(1000, 9999) . ' ' . strtoupper(Str::random(2)),
                'merk_mobil'   => $merkMobil[array_rand($merkMobil)],
                'no_seri'      => 'SERI' . rand(10000, 20000),
                'no_unit'      => 'Unit' . rand(10, 20),
                'nama_tim'     => $namaTim[array_rand($namaTim)],
                'status_mobil' => $statusMobil[array_rand($statusMobil)],
            ]);
            $mobilIds[] = $mobil->id;
        }

        // ✅ Tambah Alat
        $kategori = ['distribusi', 'pemeliharaan', 'proteksi', 'pengukuran', 'energi_terbarukan', 'pendukung'];
        $merekAlat = ['Fluke', 'Kyoritsu', 'Hioki', 'UNI-T'];
        $statusAlat = ['Baik', 'Rusak', 'Hilang'];

        $alatIds = [];
        foreach ($mobilIds as $mobilId) {
            foreach (range(1, 45) as $i) {
                $alat = Alat::create([
                    'mobil_id'      => $mobilId,
                    'kode_barcode'  => 'QR-' . strtoupper(Str::random(8)),
                    'nama_alat'     => 'Alat ' . rand(1, 100),
                    'kategori_alat' => $kategori[array_rand($kategori)],
                    'merek_alat'    => $merekAlat[array_rand($merekAlat)],
                    'spesifikasi'   => 'Spesifikasi ' . rand(1, 50),
                    'tanggal_masuk' => date('Y-m-d', strtotime('-' . rand(30, 1000) . ' days')),
                    'status_alat'   => $statusAlat[array_rand($statusAlat)],
                    'foto'          => 'https://picsum.photos/seed/' . rand(1, 10000) . '/400/400',
                ]);
                $alatIds[] = $alat->id;
            }
        }

        // ✅ Tambah Gelar
        $keterangan = [
            'Tidak ada masalah',
            'Perlu pengecekan ulang',
            'Alat terlihat aus',
            'Butuh kalibrasi',
            'Komponen hilang sebagian',
        ];

        foreach (range(1, 35) as $i) {
            $gelar = Gelar::create([
                'mobil_id'    => $mobilIds[array_rand($mobilIds)],
                'pelaksana'   => 'Petugas ' . rand(1, 50),
                'status'      => 'Lengkap',
                'tanggal_cek' => date('Y-m-d', strtotime('-' . rand(1, 30) . ' days')),
            ]);

            $alatDipakai = collect($alatIds)->random(rand(3, 7));
            $statusGelar = 'Lengkap';

            foreach ($alatDipakai as $alatId) {
                $kondisi = $statusAlat[array_rand($statusAlat)];

                DB::table('detail_gelars')->insert([
                    'gelar_id'     => $gelar->id,
                    'alat_id'      => $alatId,
                    'status_alat'  => $kondisi,
                    'keterangan'   => $keterangan[array_rand($keterangan)],
                    'foto_kondisi' => 'https://loremflickr.com/400/400/tools?random=' . rand(1, 10000),
                    'created_at'   => now(),
                    'updated_at'   => now(),
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
