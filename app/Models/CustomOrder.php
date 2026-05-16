<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomOrder extends Model
{
    protected $fillable = [
        'order_id',
        'request_note',
        'ukuran',
        'warna',
        'tambahan_aksesoris',
        'status',
        'remarks_assessment',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}