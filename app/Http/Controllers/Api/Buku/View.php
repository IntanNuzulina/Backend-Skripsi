<?php

namespace App\Http\Controllers\Api\Buku;

use App\Http\Controllers\Controller;
use App\Http\Resources\BukuResource;
use App\Models\Buku;
use App\Models\FlashSale;
use Exception;
use Illuminate\Http\Request;

class View extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        // $view = Buku::get();
        // $getFlashSale = [];

        // foreach ($view as $key => $value) {
        //     if ($value['id_flash_sales'] != null) {
        //         $id_flash_sales = $value['id_flash_sales'];
        //         $flashSale = FlashSale::where('id', $id_flash_sales)->first();
        //         if ($flashSale) {
        //             $value['diskon'] = $flashSale;
        //             $value['harga_diskon'] = $value['harga'] * (1 - ($flashSale['diskon'] / 100));
        //             unset($flashSale['updated_at']);
        //             unset($flashSale['created_at']);
        //         }
        //     }
        //     unset($value['created_at']);
        //     unset($value['updated_at']);
        //     $value['images'] = env('APP_URL') . $value->gambar;
        //     unset($value['gambar']);
        //     $getFlashSale[] = $value;
        // }

        // if (count($getFlashSale) > 0) {
        //     return response()->json([
        //         'status' => 'success',
        //         'message' => 'berhasil mendapatkan buku',
        //         'data' => $getFlashSale,
        //     ]);
        // } else {
        //     return response()->json([
        //         'status' => 'error',
        //         'message' => 'tidak ada buku tersedia',
        //         'row' => 0,
        //         'data' => [],
        //         'diskon' => []
        //     ]);
        // }
        $book = $request->has('latest') ? Buku::latest()->get() : Buku::all();

        if ($request->has('flashsale')) {
            $now = date('Y-m-d H:i:s');
            $book = $book->filter(function ($value) use ($now) {
                if ($value->id_flash_sales != null) {
                    $flashSale = FlashSale::where('id', $value->id_flash_sales)->first();
                    if ($flashSale && $flashSale->tanggal_akhir > $now) {
                        return $value;
                    }
                }
            });
        }
        if (!$book) {
            return response()->json([
                'error' => 'data not found'
            ], 404);
        }
        return BukuResource::collection($book);
    }

    public function show($id)
    {
        try {
            $book = Buku::find($id);
            return new BukuResource($book);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ]);
        }
    }

    public function search(Request $request)
    {
        $book = Buku::where('judul', 'like', '%' . $request->q . '%')->get();
        return BukuResource::collection($book);
    }
}
