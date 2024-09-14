<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use Illuminate\Support\Facades\Route;

Route::controller(AuthController::class)->prefix('auth')->name('auth')->group(function () {
    Route::post('register','register');
    Route::post('login','login');
    Route::post('forgot-password','forgotPassword');
    Route::post('reset-password','resetPassword');
});

Route::apiResource('category', CategoryController::class)->only('index');

Route::get('/test', function() {
    echo "test";
});

//TODO: ->middleware('auth:sanctum')
