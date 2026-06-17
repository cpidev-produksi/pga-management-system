<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

/**
 * BACKFILL DATA LAMA + FINALISASI plant_uuid.
 *
 * Semua data lama (single-plant) ditetapkan ke plant default berkode "SLT" (Salatiga).
 * Ubah konstanta DEFAULT_PLANT_CODE di bawah bila plant awal Anda berbeda.
 *
 * Memakai query builder (bukan Eloquent) agar tidak terpengaruh global scope plant.
 */
return new class extends Migration
{
    /** Kode plant tujuan untuk seluruh data lama. */
    private const DEFAULT_PLANT_CODE = 'SLT';

    public function up(): void
    {
        // 1. Pastikan plant default ada (resolve-or-create), agar migration mandiri
        //    meski PlantsSeeder belum dijalankan.
        $plant = DB::table('plants')->where('code', self::DEFAULT_PLANT_CODE)->first();

        if (! $plant) {
            $uuid = (string) Str::uuid();
            DB::table('plants')->insert([
                'uuid'        => $uuid,
                'name'        => 'Salatiga',
                'code'        => self::DEFAULT_PLANT_CODE,
                'location'    => null,
                'description' => 'Plant default (dibuat otomatis saat backfill).',
                'is_active'   => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
            $plantUuid = $uuid;
        } else {
            $plantUuid = $plant->uuid;
        }

        // 2. Backfill data lama yang plant_uuid-nya masih NULL.
        DB::table('visitors')->whereNull('plant_uuid')->update(['plant_uuid' => $plantUuid]);
        DB::table('users')
            ->whereNull('plant_uuid')
            ->where('is_super_admin', false)
            ->update(['plant_uuid' => $plantUuid]);

        // 3. Jadikan visitors.plant_uuid WAJIB (setiap visitor harus punya plant).
        //    users.plant_uuid tetap nullable (Super Admin = NULL).
        Schema::table('visitors', function (Blueprint $table) {
            $table->uuid('plant_uuid')->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('visitors', function (Blueprint $table) {
            $table->uuid('plant_uuid')->nullable()->change();
        });
        // Data backfill sengaja tidak di-rollback (tidak bisa membedakan mana yang asli NULL).
    }
};
