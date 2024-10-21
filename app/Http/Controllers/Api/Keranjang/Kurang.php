<?php

namespace App\Http\Controllers\Api\Keranjang;

use App\Http\Controllers\Controller;
use App\Models\Keranjang;
use Illuminate\Http\Request;

class Kurang extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $request->validate([
            'keranjang_id' => 'required|exists:keranjangs,id',
        ]);

        $findKeranjang = Keranjang::find($request->input('keranjang_id'));

        $qtyS = $findKeranjang->qty;

        if ($qtyS == 1) {
            return response()->json([
                'status' => 'error',
                'message' => 'minimal qty 1 tidak bisa di kurangkan lagi.',
            ]);
        }

        $hargaS = $findKeranjang->harga;

        $qtyN = $qtyS - 1;
        $totalHargaN = $hargaS * $qtyN;

        $update = $findKeranjang->update([
            'qty' => $qtyN,
            'total_harga' => $totalHargaN,
        ]);

        if ($update) {
            return response()->json([
                'status' => 'success',
                'message' => 'keranjang berhasil dikurangkan',
                'data' => $findKeranjang
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'keranjang gagal diupdate'
        ], 500);
    }
}
