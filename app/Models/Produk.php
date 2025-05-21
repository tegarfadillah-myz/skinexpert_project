<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class Produk extends Model
{
    use HasFactory;

    protected $table = 'produk';

    protected $primaryKey = 'id_produk';

    protected $fillable = [
        'nama_produk',
        'slug',
        'deskripsi_produk',
        'harga',
        'stok',
        'gambar_produk',
        'nama_toko',
        'kategori',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($produk) {
            $produk->slug = Str::slug($produk->nama_produk);
        });

        static::updating(function ($produk) {
            $produk->slug = Str::slug($produk->nama_produk);
        });

        static::deleting(function ($produk) {
            if ($produk->gambar_produk) {
                $filePath = public_path('storage/' . $produk->gambar_produk);
                if (File::exists($filePath)) {
                    File::delete($filePath);
                    Log::info("File terhapus: " . $filePath);
                }
            }
        });
    }
}
