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
            'email' => 'admin@pga.com',
            'password' => Hash::make('semangat'),
            'is_contactable' => true,
            'email_verified_at' => now(),
            // 'role_uuid' => ...  <-- HAPUS BARIS INI (JANGAN ADA LAGI) 
            // 'department_uuid' => ... (Biarkan jika Anda masih pakai department)
        ]);

        // 2. Assign Role menggunakan Spatie
        // Pastikan Role 'Admin' sudah dibuat di RolePermissionSeeder sebelumnya
        $admin->assignRole('Admin');

        // --- Contoh User Lain ---
        $staff = User::create([
            'name' => 'Staf Biasa',
            'email' => 'staff@pga.com',
            'password' => Hash::make('password'),
            'is_contactable' => true,
        ]);
        
        $staff->assignRole('User');
    }
}