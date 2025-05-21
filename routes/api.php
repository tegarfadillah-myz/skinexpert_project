<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ConsultationController;
use App\Http\Controllers\Api\MessageController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\api\DokterController;
use App\Http\Controllers\api\ArticleController;
use App\Http\Controllers\api\ProdukController;

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

    Route::post('/articles', [ArticleController::class, 'store']);
});

Route::prefix('dokter')->group(function () {
    Route::get('/', [DokterController::class, 'index']);
    Route::post('/', [DokterController::class, 'store']);
    Route::get('{id}', [DokterController::class, 'show']);
    Route::put('{id}', [DokterController::class, 'update']);
    Route::delete('{id}', [DokterController::class, 'destroy']);
});


Route::prefix('produk')->controller(ProdukController::class)->group(function () {
    Route::get('/', 'index');
    Route::post('/', 'store');
    Route::get('/{slug}', 'show');
    Route::put('/{id}', 'update');
    Route::delete('/{id}', 'destroy');
});

Route::get('/articles', [ArticleController::class, 'index']);
Route::get('/articles/{id}', [ArticleController::class, 'show']);
