<?php 
use Illuminate\Support\Facades\Route;

Route::get('/api', function () {
    return response()->json(['message' => 'API Endpoint']);
});

Route::middleware(['auth', 'admin'])->group(function () {
    // Route::apiResource('products', \App\Http\Controllers\ProductController::class);
    //create a specific route for product creation
    Route::post('/api/products', [\App\Http\Controllers\ProductController::class, 'store']);
    Route::get('/api/products', [\App\Http\Controllers\ProductController::class, 'index']);
    Route::get('/api/products/{product}', [\App\Http\Controllers\ProductController::class, 'show']);
    Route::put('/api/products/{product}', [\App\Http\Controllers\ProductController::class, 'update']);
    Route::delete('/api/products/{product}', [\App\Http\Controllers\ProductController::class, 'destroy']);
});