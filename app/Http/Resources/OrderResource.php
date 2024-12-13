<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            'order_id' => $this->order_id,
            'buku' => new BukuResource($this->buku),
            'user' => new UserResource($this->user),
            'status' => $this->status,
            'harga' => $this->harga,
            'qty' => $this->qty,
            'total' => $this->qty * $this->harga,
            'alamat_penerima' => $this->alamat_penerima,
            'token' => $this->token,
            'created_at' => $this->created_at
        ];
    }
}
