<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesReport extends Model
{
    protected $fillable = [
        'order_id',
        'date',
        'total_orders',
        'total_revenue',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
