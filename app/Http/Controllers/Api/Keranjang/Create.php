<?php

namespace App\Http\Controllers\Api\Keranjang;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\Keranjang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Create extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $request->validate([
            'buku_id' => 'required|exists:bukus,id',
            'harga' => 'required|integer',
            'total_harga' => 'required|integer',
        ]);

        $user = Auth::user();

        $findBuku = Buku::where('id', $request->buku_id)->firstOrFail();

        $keranjang = Keranjang::where('user_id', $user->id)->where('buku_id', $findBuku->id)->first();

        if ($keranjang) {
            $keranjang->update([
                'qty' => $keranjang->qty + 1,
                'total_harga' => $keranjang->total_harga + $findBuku->harga
            ]);
            return response()->json([
                'status' => 'success',
                'message' => 'Buku berhasil ditambahkan ke keranjang',
                'jumlah_keranjang' => Keranjang::where('user_id', $user->id)->count(),
            ]);
        } else {
            $createKeranjang = Keranjang::create([
                'user_id' => $user->id,
                'buku_id' => $findBuku->id,
                'qty' => 1,
                'harga' => $findBuku->harga,
                'total_harga' => $findBuku->harga,
            ]);
            if ($createKeranjang) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Buku berhasil ditambahkan ke keranjang',
                    'data' => $createKeranjang,
                    'jumlah_keranjang' => Keranjang::where('user_id', $user->id)->count()
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Buku gagal ditambahkan ke keranjang',
                    'data' => null,
                ], 500);
            }
        }


    }
}
