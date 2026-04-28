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
        Schema::table('visitors', function (Blueprint $table) {
            // Menambahkan kolom boolean (true/false)
            // Default 0 (false/Tidak)
            // Diletakkan setelah kolom purpose_note (agar rapi di database)
            $table->boolean('is_production')
                  ->default(false)
                  ->after('purpose_note')
                  ->comment('1 = Masuk Produksi, 0 = Tidak');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('visitors', function (Blueprint $table) {
            $table->dropColumn('is_production');
        });
    }
};