<?php

use App\Http\Controllers\MovieController;
use App\Http\Controllers\ReviewController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::middleware(['api.key'])->group(function(){
    Route::apiResource('movies', MovieController::class);
    Route::apiResource('reviews', ReviewController::class)->only(['store']);
});
