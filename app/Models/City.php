<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $fillable = ['province_id', 'city'];

    public function province()
    {
        return $this->belongsTo(Province::class, 'province_id');
    }

    public function profiles()
    {
        return $this->hasMany(Profile::class, 'city_id');
    }
}
