<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expedition extends Model
{
    protected $table = 'expeditions';

    protected $fillable = [
        'name_expedition',
        'shipping_cost',
        'estimated_days',
    ];

    public function shippings()
    {
        return $this->hasMany(Shipping::class, 'expedition_id');
    }

    public function cities()
    {
        return $this->belongsToMany(City::class, 'city_expeditions', 'expedition_id', 'city_id')
                    ->withPivot(['shipping_cost', 'estimated_days'])
                    ->withTimestamps();
    }

    public function cityExpeditions()
    {
        return $this->hasMany(CityExpedition::class, 'expedition_id');
    }
}
