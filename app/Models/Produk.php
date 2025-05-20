<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class Produk extends Model
{
    use HasFactory;
    protected $table = 'produk';
    protected $fillable = [
        'nama_produk',
        'slug',
        'deskripsi_produk',
        'harga',
        'stok',
        'gambar_produk',
        'nama_toko',
        'kategori'
    ];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($produk) {
            if ($produk->gambar_produk) {
                $filePath = public_path('storage/' . $produk->gambar_produk);
                if (File::exists($filePath)) {
                    File::delete($filePath);
                    Log::info("File terhapus: " . $filePath);
                } else {
                    Log::warning("File tidak ditemukan: " . $filePath);
                }
            }
        });
    }
    protected static function booted()
    {
        static::creating(function ($produk) {
            $produk->slug = Str::slug($produk->nama_produk);
        });
    }

}
