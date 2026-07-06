<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Midtrans\Config;
use Midtrans\Snap;

class PaymentController extends Controller
{
    public function __construct()
    {
        Config::$serverKey    = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized  = config('midtrans.is_sanitized');
        Config::$is3ds        = config('midtrans.is_3ds');
    }

    // Halaman pilih pembayaran
    public function show(Order $order)
    {
        // Pastikan order milik customer yang login
        if ($order->profile?->users_id !== Auth::id()) {
            abort(403);
        }

        $snapToken = $this->generateSnapToken($order);

        return view('customer.payment', compact('order', 'snapToken'));
    }

    // Generate Snap Token dari Midtrans
    private function generateSnapToken(Order $order)
    {
        $orderId = 'ORDER-' . $order->id . '-' . time();

        // Simpan orderId ke database payment agar gampang diakses
        $payment = Payment::firstOrNew(['order_id' => $order->id]);
        $payment->transaction_id = $orderId;
        $payment->payment_status = 'pending';
        $payment->payment_type = 'midtrans';
        $payment->save();

        // Simpan order_id ke session supaya bisa dipakai checkStatus
        session(['midtrans_order_id_' . $order->id => $orderId]);

        $params = [
            'transaction_details' => [
                'order_id'     => $orderId,
                'gross_amount' => (int) $order->total_price,
            ],
            'customer_details' => [
                'first_name' => $order->profile?->name,
                'email'      => $order->profile?->user?->email,
            ],
            'item_details' => $order->orderItems->map(function ($item) {
                return [
                    'id'       => (string) $item->product_id,
                    'price'    => (int) $item->price,
                    'quantity' => $item->qty,
                    'name'     => $item->product->product_name,
                ];
            })->toArray(),
        ];

        return Snap::getSnapToken($params);
    }

    // Callback dari Midtrans (notification)
    public function callback(Request $request)
    {
        $serverKey = config('midtrans.server_key');
        $hashedKey = hash('sha512', 
            $request->order_id . 
            $request->status_code . 
            $request->gross_amount . 
            $serverKey
        );

        if ($hashedKey !== $request->signature_key) {
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $parts   = explode('-', $request->order_id);
        $orderId = $parts[1] ?? null;
        $order   = Order::find($orderId);

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        $payment = Payment::firstOrNew(['order_id' => $order->id]);
        $payment->payment_type   = $request->payment_type ?? 'midtrans';
        $payment->transaction_id = $request->transaction_id ?? $request->order_id;

        $transactionStatus = $request->transaction_status ?? 'pending';

        if (in_array($transactionStatus, ['capture', 'settlement'])) {
            $payment->payment_status = 'paid';
            $payment->payment_date   = now();

        } elseif ($transactionStatus === 'pending') {
            $payment->payment_status = 'pending';

        } else {
            $payment->payment_status = 'failed';
            $order->update(['status' => 'batal']);
        }

        $payment->save();

        return response()->json(['message' => 'OK']);
    }

    public function checkStatus(Order $order)
    {
        try {
            // Ambil orderId dari database pembayaran, fallback ke session
            $paymentRecord = Payment::where('order_id', $order->id)->first();
            $orderId = $paymentRecord?->transaction_id ?? session('midtrans_order_id_' . $order->id);

            if (!$orderId) {
                return redirect()->route('payment.show', $order->id)
                    ->with('error', 'Transaksi tidak ditemukan. Silakan bayar ulang.');
            }

            // Jika transaction_id di DB sudah berubah menjadi UUID Midtrans karena callback berhasil mendahului
            if (strpos($orderId, 'ORDER-') !== 0) {
                $sessionOrderId = session('midtrans_order_id_' . $order->id);
                if ($sessionOrderId) {
                    $orderId = $sessionOrderId;
                } else {
                    if ($paymentRecord && $paymentRecord->payment_status === 'paid') {
                        return redirect()->route('payment.success', $order->id);
                    }
                    return redirect()->route('payment.show', $order->id)
                        ->with('error', 'Tidak dapat memverifikasi status pembayaran. Silakan hubungi admin.');
                }
            }

            $status  = \Midtrans\Transaction::status($orderId);

            $payment = Payment::firstOrNew(['order_id' => $order->id]);
            $payment->payment_type   = $status->payment_type ?? 'midtrans';
            $payment->transaction_id = $status->transaction_id ?? $orderId;

            $transactionStatus = $status->transaction_status ?? 'pending';

            if (in_array($transactionStatus, ['capture', 'settlement'])) {
                $payment->payment_status = 'paid';
                $payment->payment_date   = now();

            } elseif ($transactionStatus === 'pending') {
                $payment->payment_status = 'pending';

            } else {
                $payment->payment_status = 'failed';
                $order->update(['status' => 'batal']);
            }

            $payment->save();

            // Hapus session setelah dipakai
            Session::forget('midtrans_order_id_' . $order->id);

            return redirect()->route('payment.success', $order->id);

        } catch (\Exception $e) {
            return redirect()->route('payment.show', $order->id)
                ->with('error', 'Gagal cek status: ' . $e->getMessage());
        }
    }

    // Halaman sukses setelah bayar
    public function success(Order $order)
    {
        return view('customer.payment-success', compact('order'));
    }
}