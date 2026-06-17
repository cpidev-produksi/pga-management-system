<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // plant_uuid: NULL = Super Admin (bisa akses semua plant)
            // plant_uuid: has value = User terikat ke 1 plant
            $table->foreignUuid('plant_uuid')
                ->nullable()
                ->after('password')
                ->constrained('plants', 'uuid')
                ->nullOnDelete();

            // Flag untuk super admin
            $table->boolean('is_super_admin')->default(false)->after('plant_uuid');

            // Index untuk performa query
            $table->index('plant_uuid');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['plant_uuid']);
            $table->dropIndex(['plant_uuid']);
            $table->dropColumn(['plant_uuid', 'is_super_admin']);
        });
    }
};
