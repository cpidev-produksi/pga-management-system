<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str; // Import class Str untuk menggunakan UUID

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

    /**
     * Boot method untuk model.
     * Akan otomatis mengisi UUID saat data baru dibuat.
     */
    protected static function booted(): void
    {
        static::creating(function ($model) {
            // Jika uuid belum diisi, buatkan yang baru
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
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
}