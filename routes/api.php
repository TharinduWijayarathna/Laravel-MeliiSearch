<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdvertisementController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Advertisement API Routes
Route::prefix('advertisements')->group(function () {
    // Basic CRUD operations
    Route::get('/', [AdvertisementController::class, 'index']);
    Route::post('/', [AdvertisementController::class, 'store']);
    Route::get('/{id}', [AdvertisementController::class, 'show']);
    Route::put('/{id}', [AdvertisementController::class, 'update']);
    Route::delete('/{id}', [AdvertisementController::class, 'destroy']);
    
    // Advanced search functionality
    Route::get('/search/advanced', [AdvertisementController::class, 'advancedSearch']);
    Route::get('/search/suggestions', [AdvertisementController::class, 'suggestions']);
});

// Health check endpoint
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now()->toISOString(),
        'service' => 'Melli Search API'
    ]);
});
