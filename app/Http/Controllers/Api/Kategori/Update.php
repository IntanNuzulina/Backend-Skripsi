<?php

namespace App\Http\Controllers\Api\Kategori;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use Illuminate\Http\Request;

class Update extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, $id)
    {
        $request->validate([
            'kategori' => 'string|required',
        ]);

        $updated = Kategori::where('id', $id)
            ->update(['kategori' => $request->kategori]);

        if (!$updated) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal memperbarui kategori',
            ], 500);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Kategori berhasil diupdate',
            'data' => Kategori::find($id),
        ]);
    }
}
