<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::create([
            'name'     => 'Admin Utama',
            'email'    => 'admin@homestay.com',
            'password' => Hash::make('password'),
            'phone'    => '081200000001',
            'role'     => 'admin',
            'email_verified_at' => now(),
        ]);

        // Owner 1
        User::create([
            'name'     => 'Budi Santoso',
            'email'    => 'budi@homestay.com',
            'password' => Hash::make('password'),
            'phone'    => '081200000002',
            'role'     => 'owner',
            'email_verified_at' => now(),
        ]);

        // Owner 2
        User::create([
            'name'     => 'Siti Rahayu',
            'email'    => 'siti@homestay.com',
            'password' => Hash::make('password'),
            'phone'    => '081200000003',
            'role'     => 'owner',
            'email_verified_at' => now(),
        ]);

        // Guest 1
        User::create([
            'name'     => 'Andi Wijaya',
            'email'    => 'andi@gmail.com',
            'password' => Hash::make('password'),
            'phone'    => '081200000004',
            'role'     => 'guest',
            'email_verified_at' => now(),
        ]);

        // Guest 2
        User::create([
            'name'     => 'Rina Kusuma',
            'email'    => 'rina@gmail.com',
            'password' => Hash::make('password'),
            'phone'    => '081200000005',
            'role'     => 'guest',
            'email_verified_at' => now(),
        ]);
    }
}
