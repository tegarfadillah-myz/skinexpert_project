<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProdukResource;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class ProdukController extends Controller
{
    public function index()
    {
        return response()->json([
            'status' => true,
            'message' => 'List produk berhasil diambil',
            'data' => ProdukResource::collection(Produk::all()),
        ]);
    }

    public function show($slug)
    {
        $produk = Produk::where('slug', $slug)->first();

        if (!$produk) {
            return response()->json([
                'status' => false,
                'message' => 'Produk tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Detail produk berhasil diambil',
            'data' => new ProdukResource($produk),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_produk' => 'required|string',
            'deskripsi_produk' => 'nullable|string',
            'harga' => 'required|numeric',
            'stok' => 'required|integer',
            'nama_toko' => 'required|string',
            'kategori' => 'required|in:mustraizer,fashwas,serum,sunscreen,produk lainnya',
            'gambar_produk' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('gambar_produk')) {
            $filename = $request->file('gambar_produk')->store('asset/gambar');
            $validated['gambar_produk'] = $filename;
        }

        $produk = Produk::create($validated);

        return response()->json([
            'status' => true,
            'message' => 'Produk berhasil ditambahkan',
            'data' => new ProdukResource($produk),
        ], 201);
    }

    public function update(Request $request, $slug)
    {
        $produk = Produk::where('slug', $slug)->first();

        if (!$produk) {
            return response()->json([
                'status' => false,
                'message' => 'Produk tidak ditemukan',
            ], 404);
        }

        $validated = $request->validate([
            'nama_produk' => 'sometimes|required|string',
            'deskripsi_produk' => 'nullable|string',
            'harga' => 'sometimes|required|numeric',
            'stok' => 'sometimes|required|integer',
            'nama_toko' => 'sometimes|required|string',
            'kategori' => 'sometimes|required|in:mustraizer,fashwas,serum,sunscreen,produk lainnya',
            'gambar_produk' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('gambar_produk')) {
            if ($produk->gambar_produk && Storage::exists($produk->gambar_produk)) {
                Storage::delete($produk->gambar_produk);
            }

            $path = $request->file('gambar_produk')->store('asset/gambar');
            $validated['gambar_produk'] = $path;
        }

        $produk->update($validated);

        return response()->json([
            'status' => true,
            'message' => 'Produk berhasil diperbarui',
            'data' => new ProdukResource($produk->fresh()),
        ]);
    }

    public function destroy($slug)
    {
        $produk = Produk::where('slug', $slug)->first();

        if (!$produk) {
            return response()->json([
                'status' => false,
                'message' => 'Produk tidak ditemukan',
            ], 404);
        }

        if ($produk->gambar_produk && File::exists(storage_path('app/' . $produk->gambar_produk))) {
            File::delete(storage_path('app/' . $produk->gambar_produk));
        }

        $produk->delete();

        return response()->json([
            'status' => true,
            'message' => 'Produk berhasil dihapus',
        ]);
    }
}
