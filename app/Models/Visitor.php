<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class Visitor extends Model
{
    use HasFactory;

    /**
     * Atribut yang dapat diisi secara massal (mass assignable).
     *
     * @var array
     */
    protected $fillable = [
        'uuid',
        'plant_uuid',
        'visit_type',
        'identity_number',
        'name',
        'age',
        'phone_number',
        'company_name',
        'address',
        //'phone_number_emrg',
        'email',
        //'internet',
        'intended_employee',
        'purpose',
        'purpose_note',
        'special_category',
        'special_needs',
        'visit_datetime',
        'group_type',
        'vehicles',
        'group_members',
        'checkout_at',
        'status',
        'scan_by',
        'checkout_by',
        'is_production',

    ];

    /**
     * Tipe data native yang akan di-cast secara otomatis.
     *
     * @var array
     */
    protected $casts = [
        'internet' => 'boolean',
        'visit_datetime' => 'datetime',
        'checkout_at' => 'datetime',
        'vehicles' => 'array', // Otomatis konversi JSON ke Array dan sebaliknya
        'group_members' => 'array', // Otomatis konversi JSON ke Array dan sebaliknya
        'status' => 'boolean',
        'is_production' => 'boolean',
    ];

    protected static function booted(): void
    {
        parent::booted();

        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }

            // Auto-isi plant_uuid dari plant aktif bila belum di-set
            // (reservasi publik mengisi ini secara eksplisit dari link plant).
            if (empty($model->plant_uuid) && session('current_plant_uuid')) {
                $model->plant_uuid = session('current_plant_uuid');
            }
        });

        // GLOBAL SCOPE PLANT (isolasi data antar-plant)
        // - Jika ada plant aktif di session (current_plant_uuid) -> filter ke plant itu.
        //   Ini berlaku untuk user biasa (di-set otomatis oleh SetCurrentPlant)
        //   MAUPUN super admin yang sedang "menyelami" satu plant.
        // - Jika super admin dalam mode "Semua Plant" (tidak ada plant aktif)
        //   -> tidak difilter, sehingga melihat seluruh plant.
        // - Request publik (belum login) tidak terpengaruh dan bekerja via uuid eksplisit.
        static::addGlobalScope('plant', function (Builder $query) {
            $plantUuid = session('current_plant_uuid');

            if ($plantUuid) {
                $query->where('plant_uuid', $plantUuid);
            }
        });
    }

    public function scanner()
    {
        return $this->belongsTo(User::class, 'scan_by', 'uuid');
    }

    public function checkouter()
    {
        return $this->belongsTo(User::class, 'checkout_by', 'uuid');
    }

    public function plant()
    {
        return $this->belongsTo(Plant::class, 'plant_uuid', 'uuid');
    }

    public function scopeByPlant(Builder $query, string $plantUuid)
    {
        return $query->where('plant_uuid', $plantUuid);
    }

    public function scopeToday(Builder $query)
    {
        return $query->whereDate('visit_datetime', today());
    }

    public function scopeDateRange(Builder $query, $startDate, $endDate)
    {
        return $query->whereBetween('visit_datetime', [$startDate, $endDate]);
    }
}