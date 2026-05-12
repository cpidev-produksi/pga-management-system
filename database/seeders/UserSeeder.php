<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role; // Pastikan pakai App\Models\Role
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat User Admin (Tanpa role_uuid)
        $admin = User::create([
            'name' => 'Aji Andana',
            'email' => 'aji.andana@cp.co.id',
            'password' => Hash::make('semangat45mengudara'),
            'is_contactable' => true,
            'email_verified_at' => now(),
        ]);

        // 2. Assign Role menggunakan Spatie
        $admin->assignRole('Admin');

        // --- Contoh User Lain ---
        $staff = User::create([
            'name' => 'Lobby Staff',
            'email' => 'ajiandana@gmail.com',
            'password' => Hash::make('password'),
            'is_contactable' => true,
        ]);
        
        $staff->assignRole('User');
    }
}