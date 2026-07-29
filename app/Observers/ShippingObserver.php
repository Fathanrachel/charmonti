<?php

namespace App\Observers;

use App\Models\Shipping;
use Illuminate\Support\Facades\Auth;

class ShippingObserver
{
    public function updated(Shipping $shipping): void
    {
        $order = $shipping->order;
        if (!$order) {
            return;
        }

        // Determine matching order status based on shipping status
        $newOrderStatus = match ($shipping->status) {
            'dikirim'           => 'dikirim',
            'sampai', 'selesai' => 'selesai',
            'pending'           => 'diproses',
            'batal'             => 'batal',
            default             => null,
        };

        $shouldSave = false;

        // Auto sync order status if matching
        if ($newOrderStatus && $order->status !== $newOrderStatus) {
            $order->status = $newOrderStatus;
            $shouldSave = true;
        }

        // Auto sync staff responsible to whoever updated the shipping if staff_id is empty or auth user is available
        if (Auth::check() && (!$order->staff_id || $shipping->wasChanged('status'))) {
            $order->staff_id = Auth::id();
            $shouldSave = true;
        }

        if ($shouldSave) {
            $order->saveQuietly();
        }
    }
}
