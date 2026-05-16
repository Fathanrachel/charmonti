<?php

namespace App\Observers;

use App\Models\Order;
use App\Models\StockLog;

class OrderObserver
{
    public function updated(Order $order): void
    {
        if ($order->wasChanged('status') && $order->status === 'diproses') {
            foreach ($order->orderItems as $item) {
                $product = $item->product;

                if ($product->materials->isEmpty()) {
                    continue;
                }

                foreach ($product->materials as $material) {
                    $totalDeduction = $material->pivot->quantity_needed * $item->quantity;
                    $material->decrement('stock', $totalDeduction);

                    StockLog::create([
                        'material_id' => $material->id,
                        'type'        => 'out',
                        'quantity'    => $totalDeduction,
                        'description' => 'Auto potong stok untuk Order #' . $order->id,
                    ]);
                }
            }
        }
    }
}