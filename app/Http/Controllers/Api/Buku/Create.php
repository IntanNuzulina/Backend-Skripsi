<?php

namespace App\Http\Controllers\Api\Buku;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use Illuminate\Http\Request;

class Create extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $request->validate([
            'id_kategori' => 'required|exists:kategoris,id',
            'id_flash_sales' => 'required|exists:flash_sales,id',
            'penerbit' => 'required|string',
            'judul' => 'required|string',
            'penulis' => 'required|string',
            'gambar' => 'required|file|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'harga' => 'required|integer',
            'deskripsi' => 'required',
            'stok' => 'required|integer',
            'halaman' => 'required|integer',
            'thn_terbit' => 'required',
            'bahasa' => 'required',
            'isbn' => 'required|string',
        ]);

        $exist = Buku::where('judul', $request->input('judul'))
                     ->orWhere('isbn', $request->input('isbn'))
                     ->first();
        if ($exist) {
            return response()->json([
                'status' => 'error',
                'message' => 'buku sudah ada',
            ], 500);
        }

        if ($request->file('gambar')) {
            $path = $request->file('gambar')->store('images', 'public');
            $nama_gambar = $path;
        }



        $create = Buku::create([
            'id_kategori' => $request->input('id_kategori'),
            'id_flash_sales' => $request->input('id_flash_sales'),
            'penerbit' => $request->input('penerbit'),
            'judul' => $request->input('judul'),
            'penulis' => $request->input('penulis'),
            'gambar' => $nama_gambar,
            'harga' => $request->input('harga'),
            'deskripsi' => $request->input('deskripsi'),
            'stok' => $request->input('stok'),
            'halaman' => $request->input('halaman'),
            'thn_terbit' => $request->input('thn_terbit'),
            'bahasa' => $request->input('bahasa'),
            'isbn' => $request->input('isbn'),
        ]);

        if (!$create) {
            return response()->json([
                'status' => 'error',
                'message' => 'buku gagal ditambahkan',
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'buku berhasil ditambahkan',
            'data' => $create
        ]);
    }
}
