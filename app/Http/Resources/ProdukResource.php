<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProdukResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nama_produk' => $this->nama_produk,
            'deskripsi_produk' => $this->deskripsi_produk,
            'harga' => $this->harga,
            'stok' => $this->stok,
            'nama_toko' => $this->nama_toko,
            'kategori' => $this->kategori,
            'slug' => $this->slug,
            'gambar_produk' => $this->gambar_produk ? asset('storage/' . $this->gambar_produk) : null,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
