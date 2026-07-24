<?php

namespace App\Observers;

use App\Models\Order;
use App\Models\CustomBahanOrder;
use App\Models\Bahan;
use App\Models\BahanMasuk;
use App\Models\ProductMasuk;
use Illuminate\Support\Facades\DB;

class OrderObserver
{
    public function updated(Order $order): void
    {
        DB::transaction(function () use ($order) {
            // 1. Stock Deduction when order changes to diproses (paid)
            if ($order->wasChanged('status') && $order->status === 'diproses') {
                // Regular Product Stock Deduction (Skip dummy 'Gelang Custom' product)
                foreach ($order->orderItems as $item) {
                    $product = $item->product;
                    if ($product && $product->product_name !== 'Gelang Custom') {
                        $product->deductStock($item->qty);
                    }
                }

                // Custom Order Materials & Straps Stock Deduction for ALL custom bracelets in this order
                $customOrders = CustomBahanOrder::where('order_id', $order->id)
                    ->with('customBahanOrderItems.bahan')
                    ->get();

                foreach ($customOrders as $customOrder) {
                    // Potong stok Tali Gelang (strap)
                    if (!empty($customOrder->warna)) {
                        $colorName = ucfirst(trim($customOrder->warna)); // 'Silver' atau 'Gold'
                        $bahanName = "Tali Gelang " . $colorName;

                        $taliBahan = Bahan::where('nama_bahan', $bahanName)->first();
                        if ($taliBahan) {
                            $taliBahan->deductStock(1);
                        }
                    }

                    // Potong stok manik-manik/charms untuk setiap item di gelang custom ini
                    foreach ($customOrder->customBahanOrderItems as $charmItem) {
                        $bahan = $charmItem->bahan;
                        if ($bahan) {
                            $qtyToDeduct = $charmItem->qty ?? 1;
                            $bahan->deductStock($qtyToDeduct);
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

                // Update status pengerjaan seluruh gelang custom dalam order menjadi batal
                $customOrders = CustomBahanOrder::where('order_id', $order->id)->get();
                foreach ($customOrders as $co) {
                    if ($co->status !== 'batal') {
                        $co->update(['status' => 'batal']);
                    }
                }

                // Kembalikan stok hanya jika pesanan sebelumnya sudah diproses/selesai (stok sudah terpotong)
                if (in_array($order->getOriginal('status'), ['diproses', 'selesai'])) {
                    // Restore Regular Products
                    foreach ($order->orderItems as $item) {
                        $product = $item->product;
                        if ($product && $product->product_name !== 'Gelang Custom') {
                            ProductMasuk::create([
                                'product_id'    => $product->id,
                                'qty_masuk'     => $item->qty,
                                'deskripsi'     => 'Pengembalian Stok (Pembatalan Order #' . $order->id . ')',
                                'tanggal_masuk' => now(),
                            ]);
                            $product->update(['sisa' => $product->dynamic_stock]);
                        }
                    }

                    // Restore Custom Materials & Straps for ALL custom bracelets in this order
                    $customOrdersWithItems = CustomBahanOrder::where('order_id', $order->id)
                        ->with('customBahanOrderItems.bahan')
                        ->get();

                    foreach ($customOrdersWithItems as $customOrder) {
                        // Restore Tali Gelang (strap)
                        if (!empty($customOrder->warna)) {
                            $colorName = ucfirst(trim($customOrder->warna));
                            $bahanName = "Tali Gelang " . $colorName;

                            $taliBahan = Bahan::where('nama_bahan', $bahanName)->first();
                            if ($taliBahan) {
                                BahanMasuk::create([
                                    'bahan_id'      => $taliBahan->id,
                                    'qty_masuk'     => 1,
                                    'deskripsi'     => 'Pengembalian Stok (Pembatalan Order #' . $order->id . ')',
                                    'tanggal_masuk' => now(),
                                ]);
                                $taliBahan->update(['sisa' => $taliBahan->dynamic_stock]);
                            }
                        }

                        // Restore Charms
                        foreach ($customOrder->customBahanOrderItems as $charmItem) {
                            $bahan = $charmItem->bahan;
                            if ($bahan) {
                                $qtyToRestore = $charmItem->qty ?? 1;
                                BahanMasuk::create([
                                    'bahan_id'      => $bahan->id,
                                    'qty_masuk'     => $qtyToRestore,
                                    'deskripsi'     => 'Pengembalian Stok (Pembatalan Order #' . $order->id . ')',
                                    'tanggal_masuk' => now(),
                                ]);
                                $bahan->update(['sisa' => $bahan->dynamic_stock]);
                            }
                        }
                    }
                }
            }
        });
    }
}