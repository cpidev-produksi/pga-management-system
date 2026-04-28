<?php

namespace App\Models;

use Spatie\Permission\Models\Role as SpatieRole;
use Illuminate\Support\Str;

class Role extends SpatieRole
{
    // HAPUS $incrementing = false; 
    // HAPUS $keyType = 'string';

    // Tambahkan ini agar nanti kalau cari role di URL pakainya UUID
    public function getRouteKeyName()
    {
        return 'uuid';
    }

    protected static function booted()
    {
        parent::booted();

        static::creating(function ($model) {
            // PERBAIKAN DISINI:
            // Isi ke kolom uuid, bukan id
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
        });
    }
}