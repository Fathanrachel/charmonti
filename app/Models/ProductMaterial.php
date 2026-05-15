<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ProductMaterial extends Pivot
{
    protected $table = 'product_material';

    protected $fillable = [
        'product_id',
        'material_id',
        'quantity_needed',
    ];
}