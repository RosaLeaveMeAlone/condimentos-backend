<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Middleware\SetCart;
use Illuminate\Support\Facades\Route;

Route::controller(AuthController::class)->prefix('auth')->group(function () {
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


Route::apiResource('cart', CartController::class)->only(['show','store']);
Route::controller(CartController::class)->prefix('cart')->group(function() {
    Route::middleware([SetCart::class])->post('/add-product', 'addProduct');
    Route::middleware([SetCart::class])->post('/close-cart', 'closeCart');
});

// Route::get('/test', function() {
//     echo "test";
// });

