<?php

use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\LogoutController;
use App\Http\Controllers\Api\Auth\RefreshAccessTokenController;

Route::prefix('auth')->group(function (): void {

    Route::post('/login', [LoginController::class, 'store']);

    Route::middleware('auth:users-api')->group(function (): void {

        Route::post('/refresh', [RefreshAccessTokenController::class, 'store']);

        Route::post('/logout', [LogoutController::class, 'destroy']);

    });

});
