<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Plant extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'uuid';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'uuid',
        'name',
        'code',
        'location',
        'description',
        'poster_image',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    public function getPosterUrlAttribute(): string
    {
        $poster = $this->poster_image;
 
        if (empty($poster)) {
            // Fallback: konvensi nama file lama berdasarkan kode plant.
            return asset('assets/img/poster-peraturan-' . strtolower($this->code) . '.png');
        }
 
        if (Str::startsWith($poster, ['http://', 'https://'])) {
            return $poster;
        }
 
        // Hasil upload disimpan di storage/app/public (disk 'public').
        if (Str::startsWith($poster, 'plants/')) {
            return asset('storage/' . $poster);
        }

        return asset($poster);
    }

    protected static function booted()
    {
        parent::booted();

        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
        });
    }

    public function users()
    {
        return $this->hasMany(User::class, 'plant_uuid', 'uuid');
    }

    public function visitors()
    {
        return $this->hasMany(Visitor::class, 'plant_uuid', 'uuid');
    }
}
