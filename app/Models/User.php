<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Lab404\Impersonate\Models\Impersonate;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, Impersonate, HasApiTokens;

    protected $fillable = [
        'name',
        'username',
        'nik',
        'email',
        'image',
        'role_id',
        'instance_id',
        'employee_id',
        'jabatan',
        'no_hp',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        // static::creating(function ($data) {

        // });

        static::created(function ($data) {
            // connect to employee table nip = user.username
            $employee = Employee::where('nip', $data->username)->first();
            if ($employee) {
                $data->employee_id = $employee->id;
                $data->save();
            }
        });

        // static::updated(function ($data) {
        //     // connect to employee table nip = user.username
        //     $employee = Employee::where('nip', $data->username)->first();
        //     if ($employee) {
        //         $data->employee_id = $employee->id;
        //         $data->save();
        //     }
        // });
    }

    public function createdSppds(): HasMany
    {
        return $this->hasMany(Sppd::class, 'created_by');
    }

    public function approvedSppds(): HasMany
    {
        return $this->hasMany(Sppd::class, 'approved_by');
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Check if user can be impersonated
     */
    public function canBeImpersonated(): bool
    {
        return true;
    }

    public function instace()
    {
        return $this->belongsTo(Instance::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Check if user can impersonate others
     */
    public function canImpersonate(): bool
    {
        return $this->email === 'admin@example.com'; // Customize this logic
    }
}
