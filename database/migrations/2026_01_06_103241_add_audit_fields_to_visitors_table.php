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
            // Menambahkan kolom scan_by setelah kolom status
            $table->char('scan_by', 36)->nullable()->after('status')
                  ->comment('Menyimpan UUID user yang melakukan scan check-in');

            // Menambahkan kolom checkout_by setelah kolom checkout_at
            $table->char('checkout_by', 36)->nullable()->after('checkout_at')
                  ->comment('Menyimpan UUID user yang melakukan proses checkout');
            
            // Opsional: Tambahkan foreign key jika ingin integritas data ketat
            // $table->foreign('scan_by')->references('uuid')->on('users')->onDelete('set null');
            // $table->foreign('checkout_by')->references('uuid')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('visitors', function (Blueprint $table) {
            $table->dropColumn(['scan_by', 'checkout_by']);
        });
    }
};