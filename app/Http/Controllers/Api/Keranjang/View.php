<?php

namespace App\Http\Controllers\Api\Keranjang;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\FlashSale;
use App\Models\Keranjang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class View extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $user = Auth::user();

        $findKeranjang = Keranjang::where('user_id', $user->id)->get();

        if ($findKeranjang->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Keranjang belum tersedia.',
                'data' => null
            ], 404);
        }

        $keranjangWithBooks = [];

        foreach ($findKeranjang as $keranjang) {
            $buku = Buku::find($keranjang->buku_id);
            if ($buku) {
                // Mendapatkan diskon jika ada
                $diskon = $buku['id_flash_sales'] ? FlashSale::where('id', $buku['id_flash_sales'])->first()['diskon'] : 0;
                // Menghitung harga_diskon
                $harga_diskon = $diskon > 0 ? $buku['harga'] * (1 - ($diskon / 100)) : $buku['harga'];
                // Menghitung total_harga_diskon
                $total_harga_diskon = $harga_diskon * $keranjang->qty;

                unset($buku['created_at']);
                unset($buku['updated_at']);
                $keranjangWithBooks[] = [
                    'keranjang_id' => $keranjang->id,
                    'user_id' => $keranjang->user_id,
                    'buku' => $buku,
                    'qty' => $keranjang->qty,
                    'harga' => $keranjang->harga,
                    'total_harga' => $keranjang->total_harga,
                    'harga_diskon' => $harga_diskon, // Menambahkan harga_diskon
                    'total_harga_diskon' => $total_harga_diskon // Menambahkan total_harga_diskon
                ];
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Keranjang berhasil ditemukan',
            'data' => $keranjangWithBooks,
        ]);


    }
}
