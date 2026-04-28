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
        Schema::create('login_activities', function (Blueprint $table) {
            $table->id();
            
            // PERBAIKAN: Gunakan tipe uuid atau string, bukan unsignedBigInteger
            // Jika user ID anda benar-benar UUID, unsignedBigInteger akan Error.
            $table->uuid('user_uuid'); 
            
            $table->string('ip_address', 45);
            $table->string('country_name')->nullable();
            $table->string('city')->nullable();
            $table->string('region_name')->nullable();
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
        Schema::dropIfExists('login_activities');
    }
};
