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
