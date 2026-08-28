<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomBahanOrder extends Model
{
    protected $table = 'custom_bahan_orders';

    protected $fillable = [
        'order_id',
        'warna',
        'request_note',
        'status',
        'remarks_assessment',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function customBahanOrderItems()
    {
        return $this->hasMany(CustomBahanOrderItem::class, 'custom_order_id');
    }
}
