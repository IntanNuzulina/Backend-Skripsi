<?php

namespace App\Http\Controllers\Api\Order;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use Illuminate\Http\Request;

class View extends Controller
{
    public function __invoke()
    {
        $order = Order::all();

        if ($order->count() == 0) {
            return response()->json([
                'message' => 'Tidak ada data'
            ], 404);
        } else {
            return OrderResource::collection($order);
        }
    }

    public function orderUser() 
    {
        $order = Order::where('user_id', auth()->user()->id)->get();

        if ($order->count() == 0) {
            return response()->json([
                'message' => 'Tidak ada data'
            ], 404);
        } else {
            return OrderResource::collection($order);
        }
    }
}
