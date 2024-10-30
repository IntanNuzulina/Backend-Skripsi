<?php

namespace App\Http\Controllers\Api\Kategori;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use Illuminate\Http\Request;

class Create extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $request->validate([
           'kategori' => 'required|unique:kategoris',
        ]);
        
        $existingCategory = Kategori::where('kategori', $request->input('kategori'))->first();

        if ($existingCategory) {
            return response()->json([
                'status' => 'error',
                'message' => 'Kategori sudah ada.',
            ], 500); 
        }
        $kategori = Kategori::create([
            'kategori' => $request->kategori
        ]);

        if (!$kategori) {
            return response()->json([
                'status' => 'error',
                'message' => 'Kategori gagal ditambahkan',
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Kategori berhasil ditambahkan',
            'data' => $kategori
        ]);

    }
}
