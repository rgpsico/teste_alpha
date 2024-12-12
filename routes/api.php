<?php

use App\Http\Controllers\Api\ContactControllerApi;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::apiResource('contacts', ContactControllerApi::class);
// Rotas públicas (sem autenticação)
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// Rotas protegidas (apenas para usuários autenticados)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
});
