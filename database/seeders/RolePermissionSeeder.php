<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Reset Cache Permission
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // 2. Daftar Permission
        $permissions = [
            'view_master_data', // Admin
            'manage_users',     // Admin
            'export_excel',     // Admin
            'export_pdf',       // Admin (User tidak dapat lagi)
            'scan_qr',          // Security
            'check_out_visitor' // Security
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // 3. Role Admin (Full Akses)
        $adminRole = Role::firstOrCreate(['name' => 'Admin']);
        $adminRole->givePermissionTo(Permission::all());

        // ==========================================
        // 4. ROLE USER (TANPA AKSES)
        // ==========================================
        $userRole = Role::firstOrCreate(['name' => 'User']);

        // ==========================================
        // 5. ROLE SECURITY
        // ==========================================
        $securityRole = Role::firstOrCreate(['name' => 'Security']);
        
        $securityRole->givePermissionTo([
            'scan_qr',
            'check_out_visitor',
        ]);
    }
}