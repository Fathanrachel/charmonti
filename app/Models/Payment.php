<?php

namespace App\Models;

use App\Models\Expedition;
use App\Models\Shipping;
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

                        // Stock deduction is handled by OrderObserver when order status changes to diproses.

                        // Buat data pengiriman otomatis jika belum ada
                        if (!$order->shipping) {
                            $expedition = Expedition::first();
                            Shipping::create([
                                'order_id' => $order->id,
                                'payment_id' => $payment->id,
                                'expedition_id' => $expedition?->id,
                                'shipping_cost' => 0,
                                'status' => 'pending',
                            ]);
                        }
                    }
                }

                // SINKRONISASI LAPORAN OTOMATIS:
                // Dapatkan tanggal pembayaran (tanpa waktu)
                $date = $payment->payment_date ? \Carbon\Carbon::parse($payment->payment_date)->toDateString() : now()->toDateString();
                
                // Ambil semua transaksi yang Lunas (paid) pada tanggal tersebut
                $paidPayments = self::where('payment_status', 'paid')
                    ->whereDate('payment_date', $date)
                    ->get();
                
                $totalOrders = $paidPayments->count();
                $totalRevenue = 0;
                
                foreach ($paidPayments as $p) {
                    if ($p->order) {
                        $totalRevenue += $p->order->total_price;
                    }
                }

                // 1. Sinkronisasi Laporan Penjualan (Sales Report)
                \App\Models\SalesReport::updateOrCreate(
                    ['date' => $date],
                    [
                        'total_orders' => $totalOrders,
                        'total_revenue' => $totalRevenue,
                    ]
                );

                // 2. Sinkronisasi Laporan Keuangan (Financial Report)
                // Asumsi: Biaya pengeluaran (expense) adalah 40% dari total pendapatan, Profit adalah 60%
                $expense = $totalRevenue * 0.40;
                $profit = $totalRevenue - $expense;

                \App\Models\FinancialReport::updateOrCreate(
                    ['date' => $date],
                    [
                        'income' => $totalRevenue,
                        'expense' => $expense,
                        'profit' => $profit,
                    ]
                );
            }
        });
    }
}
