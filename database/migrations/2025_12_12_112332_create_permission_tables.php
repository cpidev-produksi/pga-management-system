<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $tableNames = config('permission.table_names');
        $columnNames = config('permission.column_names');

        if (empty($tableNames)) {
            throw new \Exception('Error: config/permission.php not loaded. Run [php artisan config:clear] and try again.');
        }

        // 1. TABEL PERMISSIONS
        Schema::create($tableNames['permissions'], function (Blueprint $table) {
            $table->bigIncrements('id');    // Primary Key: Angka
            $table->uuid('uuid')->unique(); // Tambahan: UUID untuk URL/Unik
            $table->string('name');       
            $table->string('guard_name'); 
            $table->timestamps();

            $table->unique(['name', 'guard_name']);
        });

        // 2. TABEL ROLES
        Schema::create($tableNames['roles'], function (Blueprint $table) {
            $table->bigIncrements('id');    // Primary Key: Angka
            $table->uuid('uuid')->unique(); // Tambahan: UUID untuk URL/Unik
            $table->string('name');       
            $table->string('guard_name'); 
            $table->timestamps();

            $table->unique(['name', 'guard_name']);
        });

        // 3. TABEL MODEL_HAS_PERMISSIONS (User <-> Permission)
        Schema::create($tableNames['model_has_permissions'], function (Blueprint $table) use ($tableNames, $columnNames) {
            // Permission ID (Angka)
            $table->unsignedBigInteger(config('permission.column_names.permission_pivot_key') ?: 'permission_id');

            // User ID (UUID) - WAJIB UUID KARENA TABLE USERS ANDA PAKAI UUID
            $table->uuid($columnNames['model_morph_key']); 
            
            $table->string('model_type');
            $table->index([$columnNames['model_morph_key'], 'model_type'], 'model_has_permissions_model_id_model_type_index');

            // Foreign Key ke Permission
            $table->foreign(config('permission.column_names.permission_pivot_key') ?: 'permission_id')
                ->references('id') 
                ->on($tableNames['permissions'])
                ->onDelete('cascade');

            $table->primary([config('permission.column_names.permission_pivot_key') ?: 'permission_id', $columnNames['model_morph_key'], 'model_type'],
                    'model_has_permissions_permission_model_type_primary');
        });

        // 4. TABEL MODEL_HAS_ROLES (User <-> Role)
        Schema::create($tableNames['model_has_roles'], function (Blueprint $table) use ($tableNames, $columnNames) {
            // Role ID (Angka)
            $table->unsignedBigInteger(config('permission.column_names.role_pivot_key') ?: 'role_id');

            // User ID (UUID) - WAJIB UUID
            $table->uuid($columnNames['model_morph_key']);
            
            $table->string('model_type');
            $table->index([$columnNames['model_morph_key'], 'model_type'], 'model_has_roles_model_id_model_type_index');

            // Foreign Key ke Role
            $table->foreign(config('permission.column_names.role_pivot_key') ?: 'role_id')
                ->references('id') 
                ->on($tableNames['roles'])
                ->onDelete('cascade');

            $table->primary([config('permission.column_names.role_pivot_key') ?: 'role_id', $columnNames['model_morph_key'], 'model_type'],
                    'model_has_roles_role_model_type_primary');
        });

        // 5. TABEL ROLE_HAS_PERMISSIONS (Role <-> Permission)
        Schema::create($tableNames['role_has_permissions'], function (Blueprint $table) use ($tableNames) {
            // Keduanya Angka
            $table->unsignedBigInteger(config('permission.column_names.permission_pivot_key') ?: 'permission_id');
            $table->unsignedBigInteger(config('permission.column_names.role_pivot_key') ?: 'role_id');

            $table->foreign(config('permission.column_names.permission_pivot_key') ?: 'permission_id')
                ->references('id') 
                ->on($tableNames['permissions'])
                ->onDelete('cascade');

            $table->foreign(config('permission.column_names.role_pivot_key') ?: 'role_id')
                ->references('id') 
                ->on($tableNames['roles'])
                ->onDelete('cascade');

            $table->primary([config('permission.column_names.permission_pivot_key') ?: 'permission_id', config('permission.column_names.role_pivot_key') ?: 'role_id'],
                'role_has_permissions_permission_id_role_id_primary');
        });

        app('cache')
            ->store(config('permission.cache.store') != 'default' ? config('permission.cache.store') : null)
            ->forget(config('permission.cache.key'));
    }

    public function down(): void
    {
        $tableNames = config('permission.table_names');
        if (empty($tableNames)) return;

        Schema::dropIfExists($tableNames['role_has_permissions']);
        Schema::dropIfExists($tableNames['model_has_roles']);
        Schema::dropIfExists($tableNames['model_has_permissions']);
        Schema::dropIfExists($tableNames['roles']);
        Schema::dropIfExists($tableNames['permissions']);
    }
};