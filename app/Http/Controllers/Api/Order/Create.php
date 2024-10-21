<?php

namespace App\Http\Controllers\Api\Order;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\FlashSale;
use App\Models\Keranjang;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Throwable;

class Create extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $request->validate([
            'keranjang_id' => 'required|exists:keranjangs,id',
            'alamat_penerima' => 'required'
        ]);

        $user = auth()->user();

        $findKeranjang = Keranjang::where('id', $request->keranjang_id);

        if ($findKeranjang->get()->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Keranjang belum tersedia.',
                'data' => null
            ], 404);
        }

        $stok_Buku = Buku::where('id', $findKeranjang->first()->buku_id)->first()->stok;
        $qtyKeranjang = $findKeranjang->first()->qty;

        if ($stok_Buku == 0) {
            return response()->json([
                'status' => 'error',
                'message' => 'stok buku null',
            ], 404);
        }

        if ($stok_Buku < $qtyKeranjang) {
            return response()->json([
                'status' => 'error',
                'message' => 'stok buku tidak cukup',
            ], 401);
        }

        $keranjangWithBooks = [];

        foreach ($findKeranjang->get() as $keranjang) {
            $buku = Buku::find($keranjang->buku_id);
            if ($buku) {
                $diskon = $buku['id_flash_sales'] ? FlashSale::where('id', $buku['id_flash_sales'])->first()['diskon'] : 0;
                $harga_diskon = $diskon > 0 ? $buku['harga'] * (1 - ($diskon / 100)) : $buku['harga'];
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
                    'harga_diskon' => $harga_diskon,
                    'total_harga_diskon' => $total_harga_diskon
                ];
            }
        }

        $total_harga_diskon = $keranjangWithBooks[0]['total_harga_diskon'];
        $total_qty = $keranjangWithBooks[0]['qty'];
        $order_id = 'INV-' . Carbon::now()->timestamp;

//        midtrans
        $params = array(
            'transaction_details' => array(
                'order_id' => $order_id,
                'gross_amount' => $total_harga_diskon,
            ),
            'customer_details' => array(
                'first_name' => $user->name,
                'last_name' => '',
                'email' => $user->email,
            ),
            'page_expiry' => array(
                'duration' => 15,
                'unit' => 'minutes'
            )
        );

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Authorization' => env('MIDTRANS_TOKEN')
            ])->post(env('MIDTRANS_URL'), $params);

            $resInvouce = $response->json();

            $store = Order::create([
                'user_id' => $user->id,
                'buku_id' => $keranjang->buku_id,
                'order_id' => $order_id,
                'qty' => $total_qty,
                'harga' => $total_harga_diskon,
                'redirect_url' => $resInvouce['redirect_url'],
                'token' => $resInvouce['token'],
                'status' => 'pending',
                'alamat_penerima' => $request->alamat_penerima
            ]);

            if ($store) {

                $bukun = Buku::where('id', $keranjang->buku_id)->first();
                $newStok = $bukun->stok - $total_qty;
                $bukun->update(['stok' => $newStok]);

                Keranjang::where('id', $request->keranjang_id)->delete();

                return response()->json([
                    'status' => 'success',
                    'message' => 'Prosess berhasil silahkan lakukan pembayaran',
                    'data' => $store
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Prosess gagal.',
                    'data' => null
                ], 500);
            }

        } catch (Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
                'code' => $th->getCode(),
            ]);
        }

    }
}
