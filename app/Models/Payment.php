<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $guarded = [];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    protected static function booted()
    {
        static::saved(function ($payment) {
            // Jika status pembayaran berubah menjadi paid (lunas)
            if ($payment->payment_status === 'paid') {
                $order = $payment->order;
                if ($order) {
                    // Update status order jika status sebelumnya masih pending atau batal
                    if (in_array($order->status, ['pending', 'batal'])) {
                        $order->update([
                            'status' => 'diproses',
                            'payment_method' => $payment->payment_type ?? 'midtrans',
                        ]);

                        // Potong stok produk otomatis
                        foreach ($order->orderItems as $item) {
                            $product = $item->product;
                            if ($product) {
                                $product->decrement('stock', $item->quantity);
                            }
                        }

                        // Buat data pengiriman otomatis jika belum ada
                        if (!$order->shipping) {
                            Shipping::create([
                                'order_id' => $order->id,
                                'courier' => 'Belum ditentukan',
                                'shipping_cost' => 0,
                                'status' => 'pending',
                            ]);
                        }
                    }
                }
            }
        });
    }
}
