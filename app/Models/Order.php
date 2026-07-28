<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'profile_id',
        'staff_id',
        'order_date',
        'status',
        'total_price',
        'payment_method',
    ];

    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id');
    }

    public function profile()
    {
        return $this->belongsTo(Profile::class, 'profile_id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function shipping()
    {
        return $this->hasOne(Shipping::class);
    }

    public function customBahanOrder()
    {
        return $this->hasOne(CustomBahanOrder::class, 'order_id');
    }

    public function customBahanOrders()
    {
        return $this->hasMany(CustomBahanOrder::class, 'order_id');
    }

    public function complaints()
    {
        return $this->hasMany(Complaint::class);
    }

    public function salesReport()
    {
        return $this->hasOne(SalesReport::class, 'order_id');
    }

    public function salesReports()
    {
        return $this->hasMany(SalesReport::class, 'order_id');
    }
}
