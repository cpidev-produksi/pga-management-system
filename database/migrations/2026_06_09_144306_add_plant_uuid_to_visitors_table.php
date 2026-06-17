<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('visitors', function (Blueprint $table) {
            $table->foreignUuid('plant_uuid')
                ->nullable()
                ->after('uuid')
                ->constrained('plants', 'uuid')
                ->cascadeOnDelete();

            $table->index(['plant_uuid', 'created_at']);
            $table->index('plant_uuid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('visitors', function (Blueprint $table) {
            $table->dropForeign(['plant_uuid']);
            $table->dropIndex(['plant_uuid', 'created_at']);
            $table->dropIndex('plant_uuid');
            $table->dropColumn('plant_uuid');
        });
    }
};
