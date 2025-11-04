<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Lab404\Impersonate\Models\Impersonate;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable, Impersonate;

    protected $fillable = [
        'name',
        'username',
        'email',
        'image',
        'role_id',
        'instance_id',
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

    /**
     * Check if user can impersonate others
     */
    public function canImpersonate(): bool
    {
        return $this->email === 'admin@example.com'; // Customize this logic
    }
}
