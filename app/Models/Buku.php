<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Buku extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function flashSale()
    {
        return $this->belongsTo(FlashSale::class, 'id_flash_sales', 'id');
    }

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori', 'id');
    }
}
