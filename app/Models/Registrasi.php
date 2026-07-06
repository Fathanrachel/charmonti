<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Registrasi extends Model
{
    protected $table = 'registrasi';

    protected $fillable = [
        'profile_id',
        'users_id',
        'name',
    ];

    public function profile()
    {
        return $this->belongsTo(Profile::class, 'profile_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }
}
