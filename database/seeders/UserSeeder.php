<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'id' => 1,
            'name' => 'AdminNajwan',
            'email' => 'naufal@gmail.com',
            'password' => Hash::make('password123'),
            'created_at' => '2025-08-21 03:05:13',
            'updated_at' => '2025-08-21 03:05:13',
        ]);

        User::create([
            'id' => 2,
            'name' => 'Kiya',
            'email' => 'kiya@gmail.com',
            'password' => Hash::make('password123'),
            'created_at' => '2025-08-21 03:33:33',
            'updated_at' => '2025-08-23 23:42:06',
        ]);

        User::create([
            'id' => 3,
            'name' => 'budiansyah',
            'email' => 'budiansyah@gmail.com',
            'password' => Hash::make('password123'),
            'created_at' => '2025-08-23 22:16:33',
            'updated_at' => '2025-08-23 22:34:43',
        ]);
    }
}