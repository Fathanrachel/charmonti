<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesReport extends Model
{
    protected $fillable = [
        'date',
        'total_orders',
        'total_revenue',
    ];
}
