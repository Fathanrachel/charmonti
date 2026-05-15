<?php

namespace App\Observers;

use App\Models\Order;
use App\Models\StockLog;

class OrderObserver
{
    public function updated(Order $order): void
    {
        // Potong stok otomatis saat status order berubah jadi 'diproses'
        if ($order->isDirty('status') && $order->status === 'diproses') {
            foreach ($order->orderItems as $item) {
                $product = $item->product;

                foreach ($product->materials as $material) {
                    $totalDeduction = $material->pivot->quantity_needed * $item->quantity;

                    // Kurangi stok material
                    $material->decrement('stock', $totalDeduction);

                    // Catat di stock log
                    StockLog::create([
                        'material_id' => $material->id,
                        'type' => 'out',
                        'quantity' => $totalDeduction,
                        'description' => 'Auto potong stok untuk Order #' . $order->id,
                    ]);
                }
            }
        }
    }
}