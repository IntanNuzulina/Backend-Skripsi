<?php

namespace App\Http\Controllers\Api\Flashsale;

use App\Http\Controllers\Controller;
use App\Models\FlashSale;
use Illuminate\Http\Request;

class Delete extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke($id)
    {

        $delete = FlashSale::where('id', $id)->delete();

        if ($delete) {
            return response()->json([
                'status' => 'success',
                'message' => 'flash sale berhasil dihapus',
                'data' => $delete
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'flash sale gagal dihapus',
            ], 500);
        }
    }
}
