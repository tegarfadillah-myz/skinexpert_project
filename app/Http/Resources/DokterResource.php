<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DokterResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nama_dokter' => $this->nama_dokter,
            'harga_konsultasi' => $this->harga_konsultasi,
            'tahun_pengalaman' => $this->tahun_pengalaman,
            'kota' => $this->kota,
            'spesialisasi' => $this->spesialisasi,
            'email_dokter' => $this->email_dokter,
            'nohp_dokter' => $this->nohp_dokter,
            'status' => $this->status,
            'rating' => $this->rating,
            'deskripsi' => $this->deskripsi,
            'foto_url' => $this->foto ? asset('storage/' . str_replace('asset/', '', $this->foto)) : null,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
