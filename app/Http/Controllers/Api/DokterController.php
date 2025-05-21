<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Dokter;
use App\Http\Resources\DokterResource;
use Illuminate\Support\Facades\File;

class DokterController extends Controller
{
    public function index()
    {
        return response()->json([
            'status' => true,
            'message' => 'List data dokter',
            'data' => DokterResource::collection(Dokter::all())
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_dokter' => 'required|string',
            'harga_konsultasi' => 'required|numeric',
            'tahun_pengalaman' => 'required|integer',
            'kota' => 'required|string',
            'spesialisasi' => 'required|string',
            'email_dokter' => 'required|email',
            'nohp_dokter' => 'required|string',
            'status' => 'nullable|boolean',
            'rating' => 'nullable|numeric',
            'deskripsi' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        if ($request->hasFile('foto')) {
            $filename = $request->file('foto')->hashName();
            $request->file('foto')->storeAs('asset/dokter-photos', $filename);
            $validated['foto'] = 'asset/dokter-photos/' . $filename;
        }

        $dokter = Dokter::create($validated);

        return response()->json([
            'status' => true,
            'message' => 'Dokter berhasil ditambahkan',
            'data' => new DokterResource($dokter)
        ], 201);
    }

    public function show($id)
    {
        $dokter = Dokter::findOrFail($id);

        return response()->json([
            'status' => true,
            'data' => new DokterResource($dokter)
        ]);
    }

    public function update(Request $request, $id)
    {
        $dokter = Dokter::findOrFail($id);

        $validated = $request->validate([
            'nama_dokter' => 'sometimes|required|string',
            'harga_konsultasi' => 'sometimes|required|numeric',
            'tahun_pengalaman' => 'sometimes|required|integer',
            'kota' => 'sometimes|required|string',
            'spesialisasi' => 'sometimes|required|string',
            'email_dokter' => 'sometimes|required|email',
            'nohp_dokter' => 'sometimes|required|string',
            'status' => 'nullable|boolean',
            'rating' => 'nullable|numeric',
            'deskripsi' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        if ($request->hasFile('foto')) {
            if ($dokter->foto && File::exists(storage_path('app/' . $dokter->foto))) {
                File::delete(storage_path('app/' . $dokter->foto));
            }

            $filename = $request->file('foto')->hashName();
            $request->file('foto')->storeAs('asset/dokter-photos', $filename);
            $validated['foto'] = 'asset/dokter-photos/' . $filename;
        }

        $dokter->update($validated);
        $dokter->refresh();

        return response()->json([
            'status' => true,
            'message' => 'Data dokter berhasil diperbarui',
            'data' => new DokterResource($dokter)
        ]);
    }

    public function destroy($id)
    {
        $dokter = Dokter::findOrFail($id);

        if ($dokter->foto && File::exists(storage_path('app/' . $dokter->foto))) {
            File::delete(storage_path('app/' . $dokter->foto));
        }

        $dokter->delete();

        return response()->json([
            'status' => true,
            'message' => 'Data dokter berhasil dihapus'
        ]);
    }
}
