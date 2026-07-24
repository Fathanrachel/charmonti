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

    public function expeditions()
    {
        return $this->belongsToMany(Expedition::class, 'city_expeditions', 'city_id', 'expedition_id')
                    ->withPivot(['shipping_cost', 'estimated_days'])
                    ->withTimestamps();
    }

    public function cityExpeditions()
    {
        return $this->hasMany(CityExpedition::class, 'city_id');
    }
}
