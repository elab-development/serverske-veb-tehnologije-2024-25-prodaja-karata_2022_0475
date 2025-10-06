<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;
use App\Http\Controllers\AuthController;

// =====================
// Auth rute
// =====================
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Zaštićene rute (samo autentifikovani korisnici)
Route::middleware('auth:sanctum')->group(function () {
    // Logout
    Route::post('/logout', [AuthController::class, 'logout']);

    // Zaštićene Event rute (CREATE, UPDATE, DELETE)
    Route::post('/events', [EventController::class, 'store']);
    Route::put('/events/{id}', [EventController::class, 'update']);
    Route::delete('/events/{id}', [EventController::class, 'destroy']);
});

// Javne Event rute (GET)
Route::get('/events', [EventController::class, 'index']);
Route::get('/events/{id}', [EventController::class, 'show']);

// =====================
// Test rute
// =====================
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/test', function () {
    return response()->json([
        'message' => 'API radi!',
        'status' => 'success'
    ]);
});

Route::get('/test-get', function () {
    return response()->json([
        'method' => 'GET',
        'message' => 'Ovo je GET ruta'
    ]);
});

Route::post('/test-post', function (Request $request) {
    return response()->json([
        'method' => 'POST',
        'data' => $request->all()
    ]);
});

Route::put('/test-put/{id}', function ($id, Request $request) {
    return response()->json([
        'method' => 'PUT',
        'id' => $id,
        'updated_data' => $request->all()
    ]);
});

Route::delete('/test-delete/{id}', function ($id) {
    return response()->json([
        'method' => 'DELETE',
        'id' => $id,
        'message' => "Resurs sa ID {$id} je obrisan"
    ]);
});
