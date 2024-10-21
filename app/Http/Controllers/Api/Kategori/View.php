<?php

namespace App\Http\Controllers\Api\Kategori;

use App\Http\Controllers\Controller;
use App\Http\Resources\BukuResource;
use App\Models\Buku;
use App\Models\Kategori;
use Exception;
use Illuminate\Http\Request;

class View extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $category = Kategori::get();

        if ($category->count() > 0) {
            return response()->json([
                'status' => 'success',
                'message' => 'berhasil mendapatkan kategori',
                'row' => $category->count(),
                'data' => $category
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Data tidak ditemukan'
        ], 404);

    }

    public function show($id)
    {
        try {
            $book = Buku::where('id_kategori', $id)->get();
            return BukuResource::collection($book);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ]);
        }
    }
}
