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
                // Potong stok manik-manik/charms secara akumulatif (dikelompokkan)
                $groupedItems = $order->customBahanOrder->customBahanOrderItems->groupBy('bahan_id');
                foreach ($groupedItems as $bahanId => $items) {
                    $firstItem = $items->first();
                    $bahan = $firstItem->bahan;
                    if ($bahan) {
                        $totalQty = $items->sum('qty');
                        $bahan->deductStock($totalQty);
                    }
                }

                // Potong stok Tali Gelang (strap) sesuai warna yang dipilih
                $customOrders = \App\Models\CustomBahanOrder::where('order_id', $order->id)->get();
                foreach ($customOrders as $customOrder) {
                    $colorName = ucfirst(trim($customOrder->warna)); // 'Silver' atau 'Gold'
                    $bahanName = "Tali Gelang " . $colorName; // "Tali Gelang Silver" atau "Tali Gelang Gold"

                    $taliBahan = \App\Models\Bahan::where('nama_bahan', $bahanName)->first();
                    if ($taliBahan) {
                        $taliBahan->deductStock(1);
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
                    // Kembalikan stok manik-manik/charms secara akumulatif (dikelompokkan)
                    $groupedItems = $order->customBahanOrder->customBahanOrderItems->groupBy('bahan_id');
                    foreach ($groupedItems as $bahanId => $items) {
                        $firstItem = $items->first();
                        $bahan = $firstItem->bahan;
                        if ($bahan) {
                            $totalQty = $items->sum('qty');
                            \App\Models\BahanMasuk::create([
                                'bahan_id' => $bahan->id,
                                'nama_bahan' => $bahan->nama_bahan,
                                'qty_masuk' => $totalQty,
                                'deskripsi' => 'Pengembalian Stok (Pembatalan Order #' . $order->id . ')',
                                'tanggal_masuk' => now(),
                            ]);
                        }
                    }

                    // Kembalikan stok Tali Gelang (strap) sesuai warna yang dipilih
                    $customOrders = \App\Models\CustomBahanOrder::where('order_id', $order->id)->get();
                    foreach ($customOrders as $customOrder) {
                        $colorName = ucfirst(trim($customOrder->warna)); // 'Silver' atau 'Gold'
                        $bahanName = "Tali Gelang " . $colorName;

                        $taliBahan = \App\Models\Bahan::where('nama_bahan', $bahanName)->first();
                        if ($taliBahan) {
                            \App\Models\BahanMasuk::create([
                                'bahan_id' => $taliBahan->id,
                                'nama_bahan' => $taliBahan->nama_bahan,
                                'qty_masuk' => 1,
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