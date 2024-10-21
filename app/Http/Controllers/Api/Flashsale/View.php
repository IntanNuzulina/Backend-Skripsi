<?php

namespace App\Http\Controllers\Api\Flashsale;

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
        $view = FlashSale::all();

        if ($view->count() == 0) {
            return response()->json([
                'status' => 'error',
                'message' => 'No data found',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Semua Flash Sale sudah ditampilkan',
            'data' => $view
        ]);
    }
    
    public function show($id)
    {
        try {
            $book = Buku::where('id_flash_sales', $id)->get();
            return BukuResource::collection($book);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ]);
        }
    }

}
