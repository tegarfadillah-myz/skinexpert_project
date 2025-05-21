<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProdukController extends Controller
{
    public function index()
    {
        return response()->json([
            'status' => true,
            'message' => 'List produk berhasil diambil',
            'data' => Produk::all(),
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
            'data' => $produk,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_produk' => 'required',
            'deskripsi_produk' => 'nullable',
            'harga' => 'required|numeric',
            'stok' => 'required|integer',
            'nama_toko' => 'required',
            'kategori' => 'required|in:mustraizer,fashwas,serum,sunscreen,produk lainnya',
            'gambar_produk' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('gambar_produk')) {
            $filename = $request->file('gambar_produk')->hashName();
            $request->file('gambar_produk')->storeAs('asset/gambar', $filename);
            $validated['gambar_produk'] = 'asset/gambar/' . $filename;
        }

        // Slug otomatis oleh model
        $produk = Produk::create($validated);

        return response()->json([
            'status' => true,
            'message' => 'Produk berhasil ditambahkan',
            'data' => $produk,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $produk = Produk::findOrFail($id);

        $validated = $request->validate([
            'nama_produk' => 'sometimes|required',
            'deskripsi_produk' => 'nullable',
            'harga' => 'sometimes|required|numeric',
            'stok' => 'sometimes|required|integer',
            'nama_toko' => 'sometimes|required',
            'kategori' => 'sometimes|required|in:mustraizer,fashwas,serum,sunscreen,produk lainnya',
            'gambar_produk' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('gambar_produk')) {
            $oldFilePath = storage_path('app/' . $produk->gambar_produk);
            if ($produk->gambar_produk && file_exists($oldFilePath)) {
                unlink($oldFilePath);
            }

            $filename = $request->file('gambar_produk')->hashName();
            $request->file('gambar_produk')->storeAs('asset/gambar', $filename);
            $validated['gambar_produk'] = 'asset/gambar/' . $filename;
        }

        $produk->update($validated); // Slug diperbarui otomatis di model

        return response()->json([
            'status' => true,
            'message' => 'Produk berhasil diperbarui',
            'data' => $produk,
        ]);
    }

    public function destroy($id)
    {
        $produk = Produk::findOrFail($id);

        if ($produk->gambar_produk) {
            $filePath = storage_path('app/' . $produk->gambar_produk);
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }

        $produk->delete();

        return response()->json([
            'status' => true,
            'message' => 'Produk berhasil dihapus',
        ]);
    }
}

