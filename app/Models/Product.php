<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price',
        'is_custom',
        'category',
        'image',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_custom' => 'boolean',
    ];

    public function materials()
    {
        return $this->belongsToMany(Material::class, 'product_material')
                    ->withPivot('quantity_needed');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}