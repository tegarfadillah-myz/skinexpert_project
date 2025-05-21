<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Dokter;
use Illuminate\Support\Facades\File;

class DokterController extends Controller
{
    public function index()
    {
        $data = Dokter::all();

        return response()->json([
            'status' => true,
            'message' => 'List data dokter',
            'data' => $data
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_dokter' => 'required',
            'harga_konsultasi' => 'required|numeric',
            'tahun_pengalaman' => 'required|integer',
            'kota' => 'required',
            'spesialisasi' => 'required',
            'email_dokter' => 'required|email',
            'nohp_dokter' => 'required',
            'status' => 'nullable|boolean',
            'rating' => 'nullable|numeric',
            'deskripsi' => 'nullable',
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
            'data' => $dokter
        ], 201);
    }

    public function show($id)
    {
        $dokter = Dokter::findOrFail($id);

        return response()->json([
            'status' => true,
            'data' => $dokter
        ]);
    }

    public function update(Request $request, $id)
    {
        $dokter = Dokter::findOrFail($id);

        $validated = $request->validate([
            'nama_dokter' => 'sometimes|required',
            'harga_konsultasi' => 'sometimes|required|numeric',
            'tahun_pengalaman' => 'sometimes|required|integer',
            'kota' => 'sometimes|required',
            'spesialisasi' => 'sometimes|required',
            'email_dokter' => 'sometimes|required|email',
            'nohp_dokter' => 'sometimes|required',
            'status' => 'nullable|boolean',
            'rating' => 'nullable|numeric',
            'deskripsi' => 'nullable',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        if ($request->hasFile('foto')) {
            $oldPath = storage_path('app/' . $dokter->foto);
            if ($dokter->foto && File::exists($oldPath)) {
                File::delete($oldPath);
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
            'data' => $dokter
        ]);
    }

    public function destroy($id)
    {
        $dokter = Dokter::findOrFail($id);

        $filePath = storage_path('app/' . $dokter->foto);
        if ($dokter->foto && File::exists($filePath)) {
            File::delete($filePath);
        }

        $dokter->delete();

        return response()->json([
            'status' => true,
            'message' => 'Data dokter berhasil dihapus'
        ]);
    }
}
