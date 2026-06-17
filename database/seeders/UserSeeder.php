<?php

namespace Database\Seeders;

use App\Models\Plant;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // --- Prasyarat: pastikan plant default tersedia ---
        $defaultPlantUuid = Plant::where('code', 'SLT')->value('uuid');
        if (! $defaultPlantUuid) {
            $defaultPlantUuid = Plant::firstOrCreate(
                ['code' => 'SLT'],
                ['name' => 'Salatiga', 'is_active' => true]
            )->uuid;
        }
 
        // 1. SUPER ADMIN (lintas plant, plant_uuid = null)
        $superAdmin = User::firstOrCreate(
            ['email' => 'superadmin@cp.co.id'],
            [
                'name'           => 'Super Admin',
                'password'       => Hash::make('semangat45mengudara'),
                'is_super_admin' => true,
                'plant_uuid'     => null,
            ]
        );
        $this->ensureRole($superAdmin, 'Super Admin');
 
        // 2. ADMIN (terikat plant default)
        $admin = User::firstOrCreate(
            ['email' => 'aji.andana@cp.co.id'],
            [
                'name'              => 'Aji Andana',
                'password'          => Hash::make('semangat45mengudara'),
                'is_contactable'    => true,
                'plant_uuid'        => $defaultPlantUuid,
                'email_verified_at' => now(),
            ]
        );
        $this->ensureRole($admin, 'Admin');
 
        // 3. STAFF / USER (terikat plant default)
        $staff = User::firstOrCreate(
            ['email' => 'ajiandana@gmail.com'],
            [
                'name'           => 'Lobby Staff',
                'password'       => Hash::make('password'),
                'is_contactable' => true,
                'plant_uuid'     => $defaultPlantUuid,
            ]
        );
        $this->ensureRole($staff, 'User');
    }
 
    /**
     * Pasang role hanya bila user belum memilikinya (tetap idempotent, tidak error).
     */
    private function ensureRole(User $user, string $roleName): void
    {
        if (! $user->hasRole($roleName)) {
            $user->assignRole($roleName);
        }
    }
}