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
        Schema::create('visitors', function (Blueprint $table) {
            $table->id(); 
            $table->uuid('uuid')->unique(); // Tetap unique untuk sistem internal

            // === Informasi Umum ===
            $table->string('visit_type'); 
            
            // UBAH DISINI: Hapus ->unique() agar bisa input KTP yang sama berkali-kali
            $table->string('identity_number'); 
            
            $table->string('name');
            $table->unsignedInteger('age');
            $table->string('phone_number');
            $table->string('company_name');
            $table->text('address');
            //$table->string('phone_number_emrg');
            
            // UBAH DISINI: Hapus ->unique() agar email yang sama bisa berkunjung lagi
            $table->string('email'); 

            // === Informasi Kunjungan ===
            $table->boolean('internet')->default(false);
            $table->string('intended_employee')->nullable();
            $table->string('purpose')->nullable();
            $table->text('purpose_note');
            $table->string('special_category')->nullable();
            $table->string('special_needs')->nullable();
            $table->dateTime('visit_datetime');

            // === Data Array/JSON ===
            $table->string('group_type');
            $table->json('vehicles')->nullable(); 
            $table->json('group_members')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visitors');
    }
};