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
    Route::get('/consultations', [ConsultationController::class, 'index']);
    Route::post('/consultations', [ConsultationController::class, 'store']);
    Route::get('/consultations/{id}', [ConsultationController::class, 'show']);
    Route::get('/consultations/{id}/messages', [MessageController::class, 'index']);
    Route::post('/consultations/{id}/messages', [MessageController::class, 'store']);

    Route::put('/articles/{id}', [ArticleController::class, 'update']);
    Route::delete('/articles/{id}', [ArticleController::class, 'destroy']);

    Route::post('/articles', [ArticleController::class, 'store']);
});

Route::prefix('dokter')->group(function () {
    Route::get('/', [DokterController::class, 'index']);
    Route::post('/', [DokterController::class, 'store']);
    Route::get('/{id}', [DokterController::class, 'show']);
    Route::put('/{id}', [DokterController::class, 'update']);
    Route::delete('/{id}', [DokterController::class, 'destroy']);
});

Route::prefix('produk')->group(function () {
    Route::get('/', [ProdukController::class, 'index']);
    Route::post('/', [ProdukController::class, 'store']);
    Route::get('/{slug}', [ProdukController::class, 'show']);
    Route::put('/{id}', [ProdukController::class, 'update']);
    Route::delete('/{id}', [ProdukController::class, 'destroy']);
});

Route::get('/articles', [ArticleController::class, 'index']);
Route::get('/articles/{id}', [ArticleController::class, 'show']);
