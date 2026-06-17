<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('plants', 'poster_image')) {
            Schema::table('plants', function (Blueprint $table) {
                $table->string('poster_image')->nullable()->after('description');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('plants', 'poster_image')) {
            Schema::table('plants', function (Blueprint $table) {
                $table->dropColumn('poster_image');
            });
        }
    }
};
