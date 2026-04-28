<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Panggil seeder lain secara berurutan
        // PENTING: Role harus dibuat duluan sebelum User!
        $this->call([
            RolePermissionSeeder::class,      // 1. Buat Role (Admin & User)
            UserSeeder::class,       // 3. Baru buat User
        ]);
    }


}