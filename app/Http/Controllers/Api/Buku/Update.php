<?php

namespace App\Http\Controllers\Api\Buku;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class Update extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, $id)
    {
        $validated = $request->validate([
            'id_kategori' => 'exists:kategoris,id',
            'id_flash_sales' => 'exists:flash_sales,id',
            'penerbit' => 'string',
            'judul' => 'string',
            'penulis' => 'string',
            'harga' => 'integer',
            'deskripsi' => 'string',
            'stok' => 'integer',
            'halaman' => 'integer',
            'thn_terbit' => 'integer',
            'bahasa' => 'string',
            'isbn' => 'string',
        ]);

        $book = Buku::find($id);

        if ($request->gambar != null) {
            $path = $request->file('gambar')->store('images', 'public');
            $nama_gambar = $path;
            

            Storage::disk('public')->delete($book->gambar);
                      
            $validated['gambar'] = $nama_gambar;

        }
        $update = $book->update($validated);

        if (!$update) {
            return response()->json([
                'status' => 'error',
                'message' => 'Update data gagal',
            ], 500);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Update data berhasil',
            'data' => $request->all()
        ]);
    }
}
