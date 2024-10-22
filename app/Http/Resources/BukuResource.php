<?php

namespace App\Http\Resources;

use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BukuResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'kategori' => new KategoriResource($this->kategori),
            'judul' => $this->judul,
            'penulis' => $this->penulis,
            'penerbit' => $this->penerbit,
            'deskripsi' => $this->deskripsi,
            'halaman' => $this->halaman,
            'thn_terbit' => $this->thn_terbit,
            'bahasa' => $this->bahasa,
            'harga' => $this->harga,
            'isbn' => $this->isbn,
            'tahun' => $this->tahun,
            'stok' => $this->stok,
            'image' => $this->gambar,
            'flashsale' => new FlashsaleResource($this->flashSale),
        ];
    }
}
