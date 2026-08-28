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

        // Auto sync staff responsible ONLY IF authenticated user is a staff member (not customer)
        if (Auth::check()) {
            $user = Auth::user();
            $role = $user->profile?->role ?? '';
            $isStaff = in_array($role, ['admin', 'kasir', 'stok', 'store', 'owner']);

            if ($isStaff && (!$order->staff_id || $shipping->wasChanged('status'))) {
                $order->staff_id = $user->id;
                $shouldSave = true;
            }
        }

        if ($shouldSave) {
            $order->saveQuietly();
        }
    }
}
