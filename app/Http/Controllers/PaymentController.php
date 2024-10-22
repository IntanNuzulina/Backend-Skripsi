<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Snap;

class PaymentController extends Controller
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    public function createCharge(Request $request)
    {
        $params = [
            'transaction_details' => [
                'order_id' => rand(),
                'gross_amount' => $request->amount,
            ],
            'customer_details' => [
                'first_name' => $request->first_name,
                'email' => $request->email,
                'phone' => $request->phone,
            ],
        ];
        $snapToken = Snap::getSnapToken($params);

        $order = Order::create([
            'order_id' => $params['transaction_details']['order_id'],
            'user_id' => $request->user_id,
            'buku_id' => $request->buku_id,
            'qty' => $request->qty,
            'harga' => $request->amount,
            'token' => $snapToken,
            'status' => 'unpaid',
            'alamat_penerima' => $request->alamat_penerima,
            'redirect_url' => 'https://example.com/callback'
        ]);

        return response()->json([
            'token' => $snapToken,
            'order' => $order
        ]);
    }

    public function callback(Request $request) {
        $serverKey = config('midtrans.server_key');
        $hashed = hash('sha256', $request->order_id . $request->status_code . $request->gross_amount . $serverKey);
        
        if ($request->signature_key) {
            if($request->transaction_status == 'capture') {
                $order = Order::where('order_id', $request->order_id)->first();
                $order->status = 'paid';
                $order->save();
            }
            return response()->json([
                'status' => 'success',
                'message' => 'Payment success'
            ]);
        }
    }
}