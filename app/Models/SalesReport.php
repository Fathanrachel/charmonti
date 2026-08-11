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

    public function getOrdersListAttribute(): string
    {
        if ($this->order) {
            $customerName = $this->order->profile?->name ?? 'Pelanggan';
            return "#{$this->order->id} - {$customerName}";
        }

        if ($this->date) {
            $orders = Order::whereIn('status', ['diproses', 'dikirim', 'selesai'])
                ->whereDate('order_date', $this->date)
                ->with('profile')
                ->get();

            if ($orders->isNotEmpty()) {
                return $orders->map(function ($order) {
                    $customerName = $order->profile?->name ?? 'Pelanggan';
                    return "#{$order->id} - {$customerName}";
                })->join(', ');
            }
        }

        return '-';
    }
}
