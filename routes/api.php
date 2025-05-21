<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ConsultationController;
use App\Http\Controllers\Api\MessageController;
use App\Http\Controllers\Api\AuthController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);

    // Konsultasi Routes
    Route::get('/consultations', [ConsultationController::class, 'index']);
    Route::post('/consultations', [ConsultationController::class, 'store']);
    Route::get('/consultations/{id}', [ConsultationController::class, 'show']);

    // Message Routes
    Route::get('/consultations/{id}/messages', [MessageController::class, 'index']); // get all messages in consultation
    Route::post('/consultations/{id}/messages', [MessageController::class, 'store']); // send message in consultation
});
