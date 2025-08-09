<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Translation API routes
Route::prefix('translation')->group(function () {
    Route::get('/status', [App\Http\Controllers\Api\TranslationController::class, 'status']);
    Route::post('/translate', [App\Http\Controllers\Api\TranslationController::class, 'translate']);
    Route::post('/test-observer', [App\Http\Controllers\Api\TranslationController::class, 'testObserver']);
    Route::get('/residences-status', [App\Http\Controllers\Api\TranslationController::class, 'residencesStatus']);
    Route::post('/translate-all', [App\Http\Controllers\Api\TranslationController::class, 'translateAll']);
});
