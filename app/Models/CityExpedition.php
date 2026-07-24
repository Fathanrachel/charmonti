<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CityExpedition extends Model
{
    protected $table = 'city_expeditions';

    protected $fillable = [
        'city_id',
        'expedition_id',
        'shipping_cost',
        'estimated_days',
    ];

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    public function expedition()
    {
        return $this->belongsTo(Expedition::class, 'expedition_id');
    }
}
