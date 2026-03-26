<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ExtensionProgramController;
use App\Http\Controllers\Api\CommunityController;
use App\Http\Controllers\Api\UserController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// API v1 Routes
Route::prefix('v1')->middleware(['auth:web'])->group(function () {
    // Extension Programs
    Route::apiResource('extension-programs', ExtensionProgramController::class);
    Route::post('extension-programs/{id}/cover-image', [ExtensionProgramController::class, 'uploadCoverImage']);
    Route::patch('extension-programs/bulk-status', [ExtensionProgramController::class, 'bulkUpdateStatus']);

    // Communities
    Route::apiResource('communities', CommunityController::class);

    // Users
    Route::apiResource('users', UserController::class);
    Route::get('users/me', [UserController::class, 'me']);
});

// AI Summary endpoint (can be called from authenticated pages)
Route::post('/v1/communities/{communityId}/generate-ai-summary', [CommunityController::class, 'generateAISummary'])->middleware('web');

// Public API routes (no authentication required)
Route::prefix('v1')->group(function () {
    // Authentication handled by web middleware
    Route::post('/auth/login', 'App\Http\Controllers\Api\AuthController@login');
    Route::post('/auth/register', 'App\Http\Controllers\Api\AuthController@register');
});
