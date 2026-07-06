<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expedition extends Model
{
    protected $table = 'expeditions';

    protected $fillable = [
        'name_expedition',
    ];

    public function shippings()
    {
        return $this->hasMany(Shipping::class, 'expedition_id');
    }
}
