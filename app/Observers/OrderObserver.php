<?php

namespace App\Observers;

use App\Models\Order;

class OrderObserver
{
    public function updated(Order $order): void
    {
        // 1. Stock Deduction when order changes to diproses (paid)
        if ($order->wasChanged('status') && $order->status === 'diproses') {
            // Regular Product Stock Deduction
            foreach ($order->orderItems as $item) {
                $product = $item->product;
                if ($product) {
                    $product->deductStock($item->qty);
                }
            }

            // Custom Order Materials Stock Deduction
            if ($order->customBahanOrder) {
                foreach ($order->customBahanOrder->customBahanOrderItems as $customItem) {
                    $bahan = $customItem->bahan;
                    if ($bahan) {
                        $bahan->deductStock($customItem->qty);
                    }
                }
            }
        }

        // 2. Stock Restoration when order is cancelled after being processed/completed
        if ($order->wasChanged('status') && $order->status === 'batal' && in_array($order->getOriginal('status'), ['diproses', 'selesai'])) {
            // Restore Regular Products
            foreach ($order->orderItems as $item) {
                $product = $item->product;
                if ($product) {
                    \App\Models\ProductMasuk::create([
                        'product_id' => $product->id,
                        'nama_product' => $product->product_name,
                        'qty_masuk' => $item->qty,
                        'deskripsi' => 'Pengembalian Stok (Pembatalan Order #' . $order->id . ')',
                        'tanggal_masuk' => now(),
                    ]);
                }
            }

            // Restore Custom Materials
            if ($order->customBahanOrder) {
                foreach ($order->customBahanOrder->customBahanOrderItems as $customItem) {
                    $bahan = $customItem->bahan;
                    if ($bahan) {
                        \App\Models\BahanMasuk::create([
                            'bahan_id' => $bahan->id,
                            'nama_bahan' => $bahan->nama_bahan,
                            'qty_masuk' => $customItem->qty,
                            'deskripsi' => 'Pengembalian Stok (Pembatalan Order #' . $order->id . ')',
                            'tanggal_masuk' => now(),
                        ]);
                    }
                }
            }
        }
    }
}