<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Consultation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;

class ConsultationController extends Controller
{
    public function store(Request $request)
    {
        try {
            $request->validate([
                'dokter_id' => 'required|exists:dokter,id',
            ]);

            $consultation = Consultation::create([
                'user_id' => Auth::id(),
                'dokter_id' => $request->dokter_id,
                'status' => 'ongoing',
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Konsultasi berhasil dibuat',
                'data' => $consultation
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function index()
    {
        try {
            $consultations = Consultation::with('dokter')
                ->where('user_id', Auth::id())
                ->get();

            return response()->json([
                'status' => true,
                'data' => $consultations
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal mengambil data konsultasi: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $consultation = Consultation::with(['dokter', 'messages'])
                ->where('user_id', Auth::id())
                ->where('id', $id)
                ->firstOrFail();

            return response()->json([
                'status' => true,
                'data' => $consultation
            ]);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Konsultasi tidak ditemukan'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}


