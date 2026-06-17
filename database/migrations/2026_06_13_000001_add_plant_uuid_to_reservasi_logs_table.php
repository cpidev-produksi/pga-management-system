<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reservasi_logs', function (Blueprint $table) {
            // Plant tujuan dari link reservasi yang dibuka pengunjung.
            // Nullable: log lama / akses tanpa konteks plant tetap valid.
            $table->foreignUuid('plant_uuid')
                ->nullable()
                ->after('user_uuid')
                ->constrained('plants', 'uuid')
                ->nullOnDelete();

            $table->index('plant_uuid');
        });
    }

    public function down(): void
    {
        Schema::table('reservasi_logs', function (Blueprint $table) {
            $table->dropForeign(['plant_uuid']);
            $table->dropIndex(['plant_uuid']);
            $table->dropColumn('plant_uuid');
        });
    }
};
