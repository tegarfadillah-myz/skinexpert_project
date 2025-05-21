<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class MessageController extends Controller
{
    // Kirim pesan
    public function store(Request $request, $id)
    {
        try {
            $request->validate([
                // 'consultation_id' => 'required|exists:consultations,id',
                'message' => 'required|string',
                'sender_type' => 'required|in:user,dokter',
            ]);

            $message = Message::create([
                'consultation_id' => $id,
                'sender_id' => Auth::id(),
                'sender_type' => $request->sender_type,
                'body' => $request->message,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Pesan terkirim',
                'data' => $message
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
                'message' => 'Terjadi kesalahan saat mengirim pesan: ' . $e->getMessage(),
            ], 500);
        }
    }

    // Ambil semua pesan dalam konsultasi tertentu
    public function index($consultationId)
    {
        try {
            $messages = Message::where('consultation_id', $consultationId)
                ->orderBy('created_at', 'asc')
                ->get();

            return response()->json([
                'status' => true,
                'data' => $messages
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal mengambil pesan: ' . $e->getMessage()
            ], 500);
        }
    }
}


