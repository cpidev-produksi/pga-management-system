<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('reservasi_logs', function (Blueprint $table) {
            $table->id();
            $table->uuid('user_uuid')->nullable(); // PENTING: Nullable karena user belum login
            $table->string('ip_address', 45);
            $table->string('url');
            $table->string('method'); // Tambahan: GET/POST
            $table->string('country_name')->nullable();
            $table->string('city')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservasi_logs');
    }
};
