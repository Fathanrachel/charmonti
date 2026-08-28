<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomBahanOrderItem extends Model
{
    protected $table = 'custom_bahan_order_items';

    protected $fillable = [
        'custom_order_id',
        'bahan_id',
        'qty',
    ];

    public function customBahanOrder()
    {
        return $this->belongsTo(CustomBahanOrder::class, 'custom_order_id');
    }

    public function bahan()
    {
        return $this->belongsTo(Bahan::class, 'bahan_id');
    }
}
