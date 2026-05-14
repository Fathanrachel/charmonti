<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockLog extends Model
{
    protected $fillable = [
        'material_id',
        'type',
        'quantity',
        'description',
    ];

    public function material()
    {
        return $this->belongsTo(Material::class);
    }
}