<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Consultation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConsultationController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'dokter_id' => 'required|exists:dokter,id',
        ]);

        $consultation = Consultation::create([
            'user_id' => Auth::id(),
            'dokter_id' => $request->dokter_id,
            'status' => 'ongoing',
        ]);

        return response()->json(['message' => 'Konsultasi dibuat', 'data' => $consultation]);
    }

    public function index()
    {
        $consultations = Consultation::with('dokter')
            ->where('user_id', Auth::id())
            ->get();

        return response()->json($consultations);
    }

    public function show($id)
    {
        $consultation = Consultation::with(['dokter', 'messages'])
            ->where('user_id', Auth::id())
            ->where('id', $id)
            ->firstOrFail();

        return response()->json($consultation);
    }
}

