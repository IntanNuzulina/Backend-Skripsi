<?php

namespace App\Http\Controllers\Api\Kategori;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use Illuminate\Http\Request;

class Delete extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke($id)
    {

        $delete = Kategori::find($id)->delete();

        if ($delete) {
            return response()->json([
                'status' => 'success',
                'message' => 'Kategori berhasil dihapus!'
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Kategori gagal dihapus!'
        ], 500);

    }
}
