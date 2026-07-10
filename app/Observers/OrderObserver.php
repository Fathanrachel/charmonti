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

        // 2. Stock Restoration & Status Cascading when order is cancelled
        if ($order->wasChanged('status') && $order->status === 'batal') {
            // Update status pembayaran jika ada
            if ($order->payment && $order->payment->payment_status !== 'failed') {
                $order->payment->update(['payment_status' => 'failed']);
            }

            // Update status pengiriman menjadi batal
            if ($order->shipping && $order->shipping->status !== 'batal') {
                $order->shipping->update(['status' => 'batal']);
            }

            // Update status pengerjaan gelang custom menjadi batal
            if ($order->customBahanOrder && $order->customBahanOrder->status !== 'batal') {
                $order->customBahanOrder->update(['status' => 'batal']);
            }

            // Kembalikan stok hanya jika pesanan sebelumnya sudah diproses/selesai (stok sudah terpotong)
            if (in_array($order->getOriginal('status'), ['diproses', 'selesai'])) {
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
}