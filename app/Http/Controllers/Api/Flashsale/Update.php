<?php

namespace App\Http\Controllers\Api\Flashsale;

use App\Http\Controllers\Controller;
use App\Models\FlashSale;
use Illuminate\Http\Request;

class Update extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:flash_sales,id',
            'diskon' => 'sometimes|nullable|numeric|between:0,100',
            'tanggal_awal' => 'sometimes|required',
            'tanggal_akhir' => 'sometimes|required',
        ]);

        $check = FlashSale::where('id', $request->id)->first();

        if ($check) {
            $update = FlashSale::where('id', $request->id)->update($request->all());

            if ($update) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Data berhasil diupdate',
                    'data' => $update
                ]);
            }

            return response()->json([
                'status' => 'error',
                'message' => 'Data gagal diupdate',
            ], 500);
        }
    }
}
