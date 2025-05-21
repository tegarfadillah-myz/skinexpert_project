<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    // Kirim pesan
    public function store(Request $request)
    {
        $request->validate([
            'consultation_id' => 'required|exists:consultations,id',
            'message' => 'required|string',
            'sender_type' => 'required|in:user,dokter',
        ]);

        $message = Message::create([
            'consultation_id' => $request->consultation_id,
            'sender_id' => Auth::id(),
            'sender_type' => $request->sender_type,
            'message' => $request->message,
        ]);

        return response()->json(['message' => 'Pesan terkirim', 'data' => $message]);
    }

    // Ambil semua pesan dalam konsultasi tertentu
    public function index($consultationId)
    {
        $messages = Message::where('consultation_id', $consultationId)
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json($messages);
    }
}

