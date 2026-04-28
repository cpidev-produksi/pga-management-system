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
            // BAGIAN ROLE DIHAPUS (Karena sudah dihandle otomatis oleh Spatie)
            
            // BAGIAN DEPARTMENT TETAP DIPERTAHANKAN
            // Pastikan tabel 'departments' sudah ada sebelum migrasi ini jalan
            // atau hapus constrained() jika tabel departments dibuat belakangan.
            if (!Schema::hasColumn('users', 'department_uuid')) {
                $table->foreignUuid('department_uuid')
                      ->nullable()
                      ->after('password') // Letakkan setelah password
                      ->constrained('departments', 'uuid') // Pastikan tabel departments sdh ada
                      ->nullOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Hapus department saja
            if (Schema::hasColumn('users', 'department_uuid')) {
                // Cek dulu apakah foreign key ada sebelum drop (untuk hindari error)
                try {
                    $table->dropForeign(['department_uuid']);
                } catch (\Exception $e) {}
                
                $table->dropColumn(['department_uuid']);
            }
        });
    }
};