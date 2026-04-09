<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MidtransController extends Controller
{
    public function callback(Request $request)
    {
        Log::info('Midtrans Callback Masuk!', $request->all());
        $serverKey = env('MIDTRANS_SERVER_KEY');
        $hashed = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

        if ($hashed !== $request->signature_key) {
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $order = Order::where('order_number', $request->order_id)->first();
        if (!$order) return response()->json(['message' => 'Order not found'], 404);

        $transactionStatus = $request->transaction_status;
        $payment = Payment::where('midtrans_order_id', $request->order_id)->first();

        if ($transactionStatus == 'capture' || $transactionStatus == 'settlement') {
            if ($order->status !== 'processing') {
                foreach ($order->items as $item) {
                    if ($item->variant_id) {
                        $item->variant->decrement('stock', $item->qty);
                    } else {
                        $item->product->decrement('stock', $item->qty);
                    }
                }
            }

            $order->update(['status' => 'processing']);
            $payment->update([
                'status' => 'success',
                'paid_at' => now(),
                'payment_method' => $request->payment_type
            ]);
        }

        return response()->json(['message' => 'Success']);
    }
}
