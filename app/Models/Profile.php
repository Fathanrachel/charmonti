<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $fillable = [
        'users_id',
        'city_id',
        'name',
        'phone',
        'address_line',
        'postal_code',
        'role',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'profile_id');
    }

    public function registrasi()
    {
        return $this->hasMany(Registrasi::class, 'profile_id');
    }
}
