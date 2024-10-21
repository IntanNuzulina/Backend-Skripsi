<?php

namespace App\Http\Controllers\Api\Buku;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use Illuminate\Http\Request;

class Delete extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke($id)
    {
        
        $delete = Buku::find($id)->delete();

        if ($delete) {
            return response()->json([
                'status' => 'success',
                'message' => 'Buku berhasil dihapus'
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Buku gagal dihapus'
        ], 500);
    }
}
