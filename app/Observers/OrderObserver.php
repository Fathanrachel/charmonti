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
                        $taliBahan = Bahan::where(function ($q) use ($customOrder) {
                            $q->where('nama_bahan', 'like', '%' . strtolower($customOrder->warna) . '%')
                              ->orWhere('nama_bahan', 'like', '%' . ucfirst($customOrder->warna) . '%');
                        })->where(function ($q) {
                            $q->where('nama_bahan', 'like', '%strap%')
                              ->orWhere('nama_bahan', 'like', '%Strap%')
                              ->orWhere('nama_bahan', 'like', '%tali%')
                              ->orWhere('nama_bahan', 'like', '%Tali%');
                        })->first();

                        if ($taliBahan) {
                            $taliBahan->deductStock(1);
                        }
                    }

                    // Potong stok manik-manik/charms (dikelompokkan per bahan_id agar tercatat dalam 1 baris log Bahan Keluar)
                    $groupedCharmItems = $customOrder->customBahanOrderItems->groupBy('bahan_id');
                    foreach ($groupedCharmItems as $bahanId => $charmItems) {
                        $firstItem = $charmItems->first();
                        $bahan = $firstItem?->bahan;
                        if ($bahan) {
                            $totalQtyToDeduct = $charmItems->sum('qty');
                            $bahan->deductStock($totalQtyToDeduct);
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
                            $taliBahan = Bahan::where(function ($q) use ($customOrder) {
                                $q->where('nama_bahan', 'like', '%' . strtolower($customOrder->warna) . '%')
                                  ->orWhere('nama_bahan', 'like', '%' . ucfirst($customOrder->warna) . '%');
                            })->where(function ($q) {
                                $q->where('nama_bahan', 'like', '%strap%')
                                  ->orWhere('nama_bahan', 'like', '%Strap%')
                                  ->orWhere('nama_bahan', 'like', '%tali%')
                                  ->orWhere('nama_bahan', 'like', '%Tali%');
                            })->first();

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

                        // Restore Charms (dikelompokkan per bahan_id)
                        $groupedRestoreCharms = $customOrder->customBahanOrderItems->groupBy('bahan_id');
                        foreach ($groupedRestoreCharms as $bahanId => $charmItems) {
                            $firstItem = $charmItems->first();
                            $bahan = $firstItem?->bahan;
                            if ($bahan) {
                                $totalQtyToRestore = $charmItems->sum('qty');
                                BahanMasuk::create([
                                    'bahan_id'      => $bahan->id,
                                    'qty_masuk'     => $totalQtyToRestore,
                                    'deskripsi'     => 'Pengembalian Stok (Pembatalan Order #' . $order->id . ')',
                                    'tanggal_masuk' => now(),
                                ]);
                                $bahan->update(['sisa' => $bahan->dynamic_stock]);
                            }
                        }
                    }
                }
            }

            // 3. Auto-sync SalesReport record with order_id when order status becomes diproses / selesai
            if ($order->wasChanged('status')) {
                if (in_array($order->status, ['diproses', 'selesai'])) {
                    \App\Models\SalesReport::updateOrCreate(
                        ['order_id' => $order->id],
                        [
                            'date' => $order->order_date ? \Carbon\Carbon::parse($order->order_date)->format('Y-m-d') : now()->toDateString(),
                            'total_orders' => 1,
                            'total_revenue' => $order->total_price,
                        ]
                    );
                } elseif ($order->status === 'batal') {
                    \App\Models\SalesReport::where('order_id', $order->id)->delete();
                }

                // 4. Auto-sync Shipping record when order status changes
                if (in_array($order->status, ['diproses', 'dikirim', 'selesai'])) {
                    $shippingStatus = match($order->status) {
                        'diproses' => 'pending',
                        'dikirim'  => 'dikirim',
                        'selesai'  => 'sampai',
                        default    => 'pending',
                    };
                    
                    $existingShipping = \App\Models\Shipping::where('order_id', $order->id)->first();
                    if ($existingShipping) {
                        if ($existingShipping->status !== $shippingStatus) {
                            $existingShipping->status = $shippingStatus;
                            $existingShipping->saveQuietly();
                        }
                    } else {
                        \App\Models\Shipping::create([
                            'order_id'      => $order->id,
                            'expedition_id' => 1,
                            'shipping_cost' => 10000,
                            'status'        => $shippingStatus,
                        ]);
                    }
                }
            }
        });
    }
}