<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes, HasRoles;

    // =========================================================
    // KONFIGURASI UUID
    // =========================================================
    protected $primaryKey = 'uuid';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'uuid',
        'name',
        'email',
        'password',
        'is_contactable',       
        'department_uuid',
        'plant_uuid',
        'is_super_admin',
        // 'role_uuid' <-- JANGAN ADA DISINI LAGI
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_super_admin' => 'boolean',
        ];
    }
    
    // UUID sebagai Route Key (misal: /users/{uuid}/edit)
    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    /**
     * LOGIC OTOMATIS SAAT MODEL DIBUAT
     */
    protected static function booted()
    {
        parent::booted(); // Panggil parent agar aman

        // 1. Logic: Isi UUID otomatis sebelum create (creating)
        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
        });

        // 2. Logic: Beri Role 'User' otomatis SETELAH create (created)
        static::created(function ($user) {
            try {
                // Cek apakah user ini belum punya role sama sekali
                if ($user->roles()->count() == 0) {
                    $user->assignRole('User');
                }
            } catch (\Exception $e) {
                // Biarkan kosong agar tidak error jika Role 'User' belum ada di DB (saat seeding awal)
                // \Log::warning("Auto-assign role gagal: " . $e->getMessage());
            }
        });
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_uuid', 'uuid');
    }

    public function plant()
    {
        return $this->belongsTo(Plant::class, 'plant_uuid', 'uuid');
    }

    public function isSuperAdmin(): bool
    {
        return $this->is_super_admin === true;
    }

    public function canAccessPlant(string $plantUuid): bool
    {
        if ($this->is_super_admin) {
            return true;
        }
        return $this->plant_uuid === $plantUuid;
    }

    public function getPlantName(): string
    {
        if ($this->is_super_admin) {
            return 'All Plants';
        }
        return $this->plant?->name ?? 'Unknown Plant';
    }
}