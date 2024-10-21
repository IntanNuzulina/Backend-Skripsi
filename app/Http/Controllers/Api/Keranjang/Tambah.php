<?php

namespace App\Http\Controllers\Api\Keranjang;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\Keranjang;
use Illuminate\Http\Request;

class Tambah extends Controller
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


        $buku = Buku::find($findKeranjang->buku_id);
        $stok_buku = $buku->stok;

        $qtyS = $findKeranjang->qty;
        $hargaS = $findKeranjang->harga;

        $qtyN = $qtyS + 1;

        if ($qtyN > $stok_buku) {
            return response()->json([
                'status' => 'error',
                'message' => 'Stok buku tidak cukup',
                'data' => null
            ], 401);
        }

        $totalHargaN = $hargaS * $qtyN;

        $update = $findKeranjang->update([
            'qty' => $qtyN,
            'total_harga' => $totalHargaN,
        ]);

        if ($update) {
            return response()->json([
                'status' => 'success',
                'message' => 'keranjang berhasil ditambahkan',
                'data' => $findKeranjang
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'keranjang gagal diupdate'
        ], 500);
    }
}
