<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\Buku;
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
        if ($request->has('token')) {
            $snapToken = $request->token;
            $order = Order::where('token', $snapToken)->first();
            $order->status = 'paid';
            $order->save();
        } else {
            $book = Buku::find($request->buku_id);
            $params = [
                'transaction_details' => [
                    'order_id' => rand(), 
                    'gross_amount' => $book?->flashSale?->diskon != null && $book?->flashSale?->tanggal_akhir > date('Y-m-d H:i:s') ? $request->amount - $request->amount * ($book?->flashSale?->diskon / 100) : $request->amount,
                ],
                'customer_details' => [
                    'first_name' => $request->first_name,
                    'email' => $request->email,
                    'phone' => $request->phone,
                ],
                "callbacks" => [
                    "finish"=>"http://intannuzulina.online/order"
                ]
            ];
            $snapToken = Snap::getSnapToken($params);
            $order = Order::create([
                'order_id' => $params['transaction_details']['order_id'],
                'user_id' => $request->user_id,
                'buku_id' => $request->buku_id,
                'qty' => $request->qty,
                'harga' => $params['transaction_details']['gross_amount'],
                'token' => $snapToken,
                'status' => 'unpaid',
                'alamat_penerima' => $request->alamat_penerima,
                'redirect_url' => 'http://intannuzulina.online/order'
            ]);
        }
        return response()->json([
            'token' => $snapToken,
            'order' => $order,
            'now' => $book->flashSale->tanggal_akhir > date('Y-m-d H:i:s')
        ]);
        
    }

    public function callback(Request $request) {
        $serverKey = config('midtrans.server_key');
        $hashed = hash('sha256', $request->order_id . $request->status_code . $request->gross_amount . $serverKey);
        
        if ($request->signature_key) {
            if($request->transaction_status == 'settlement') {
                $order = Order::where('order_id', $request->order_id)->first();
                $book = Buku::find($order->buku_id);
                $book->stok -= $order->qty;
                $book->save();
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