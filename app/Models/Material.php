<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    protected $fillable = [
        'name',
        'stock',
        'unit',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_material')
                    ->withPivot('quantity_needed');
    }

    public function stockLogs()
    {
        return $this->hasMany(StockLog::class);
    }
}