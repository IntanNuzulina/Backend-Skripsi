<?php

namespace App\Http\Controllers\Api\Flashsale;

use App\Http\Controllers\Controller;
use App\Models\FlashSale;
use Illuminate\Http\Request;

class Create extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $request->validate([
            'diskon' => 'numeric|between:0,100',
            'tanggal_akhir' => 'required',
        ]);
        
        $FlashSale = FlashSale::where('tanggal_akhir', '<=', $request->tanggal_akhir)->where('tanggal_akhir', '!=', null)->first();
        if ($FlashSale) {
            $FlashSale->delete();
        }

        $create = FlashSale::create($request->all());

        if (!$create) {
            return response()->json([
                'status' => 'error',
                'message' => 'Create flashsale failed',
            ], 500);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Create flashsale success',
            'data' => $create
        ]);
    }
}
