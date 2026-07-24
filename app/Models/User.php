<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements FilamentUser
{
    use Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'email',
        'password',
        'email_verified_at',
        'remember_token',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
    ];

    public function profile()
    {
        return $this->hasOne(Profile::class, 'users_id');
    }

    // Helper to get name from profile
    public function getNameAttribute()
    {
        return $this->profile?->name;
    }

    // Helper to get role from profile
    public function getRoleAttribute()
    {
        return $this->profile?->role;
    }

    // Hanya admin dan owner yang bisa akses panel Filament
    public function canAccessPanel(Panel $panel): bool
    {
        return match($panel->getId()) {
            'admin' => $this->isAdmin(),
            'owner' => $this->isOwner(),
            default => false,
        };
    }

    public function isAdmin(): bool
    {
        return $this->profile?->role === 'admin';
    }

    public function isOwner(): bool
    {
        return $this->profile?->role === 'owner';
    }

    public function isCustomer(): bool
    {
        return $this->profile?->role === 'customer';
    }

    public function orders()
    {
        return $this->hasManyThrough(Order::class, Profile::class, 'users_id', 'profile_id', 'id', 'id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function complaints()
    {
        return $this->hasMany(Complaint::class);
    }
}