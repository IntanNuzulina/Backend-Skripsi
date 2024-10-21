<?php

namespace App\Http\Controllers\Api\Keranjang;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\Keranjang;
use Auth;
use Illuminate\Http\Request;

class Delete extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke($id)
    {

        $keranjang = Keranjang::where('buku_id', $id)->first();

        if($keranjang->qty > 1) {
            $keranjang->update([
                'qty' => $keranjang->qty - 1
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil mengurangi keranjang',
                'jumlah_keranjang' => Keranjang::where('user_id', Auth::user()->id)->count(),
            ]);
        } else {
            $keranjang = $keranjang->delete();
            if ($keranjang) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Berhasil menghapus keranjang',
                    'jumlah_keranjang' => Keranjang::where('user_id', Auth::user()->id)->count(),
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Gagal menghapus keranjang'
                ], 500);
            }
        }



    }
}
