<?php

namespace App\Models;

use Spatie\Permission\Models\Permission as SpatiePermission;
use Illuminate\Support\Str;

class Permission extends SpatiePermission
{
    // HAPUS $incrementing = false; (Biarkan default true karena ID kita angka)
    // HAPUS $keyType = 'string'; (Biarkan default int)

    protected static function booted()
    {
        parent::booted();

        static::creating(function ($model) {
            // PERBAIKAN DISINI:
            // Jangan isi $model->id, tapi isi $model->uuid
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
        });
    }
}