<?php

namespace App\Observers;

use App\Models\Order;

class OrderObserver
{
    public function updated(Order $order): void
    {
        if ($order->wasChanged('status') && $order->status === 'diproses') {
            foreach ($order->orderItems as $item) {
                $product = $item->product;

                // Gelang jadi tidak potong stok
                if ($product->category === 'gelang_jadi') {
                    continue;
                }

                // Kurangi stok produk
                $product->decrement('stock', $item->quantity);
            }
        }
    }
}