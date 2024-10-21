<?php

namespace App\Http\Controllers\Api\Order;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

class Callback extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {

        $payload = $request->all();

        $orderId = $payload['order_id'];

        $order = Order::where('order_id', $orderId)->first();


        if (!$order) {
            return response()->json([
                'message' => 'Order not found'
            ]);
        }

        if($payload['transaction_status'] == 'capture'){
            $order->status = 'cancel';
            $order->save();
        }

        if($payload['transaction_status'] == 'expire'){
            $order->status = 'expire';
            $order->save();
        }

        if($payload['transaction_status'] == 'settlement'){
            $order->status = 'paid';
            $order->save();
        }
    }
}
