<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use Illuminate\Support\Facades\Route;

Route::controller(AuthController::class)->prefix('auth')->name('auth')->group(function () {
    Route::post('register','register');
    Route::post('login','login');
    Route::post('forgot-password','forgotPassword');
    Route::post('reset-password','resetPassword');
});

Route::apiResource('category', CategoryController::class)->only('index');
Route::apiResource('product', ProductController::class)->only(['index','show']);
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('product', ProductController::class)->only(['store', 'update', 'destroy']);
});

Route::get('/test', function() {
    echo "test";
});

//TODO: ->middleware('auth:sanctum')
