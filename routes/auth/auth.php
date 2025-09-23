<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;

Route::middleware('guest')->group(function () {

    Route::get('login', [LoginController::class, 'create'])
        ->name('login');

    Route::post('login', [LoginController::class, 'store'])
        ->name('login.store');

    Route::get('register', [RegisterController::class, 'create'])
        ->name('register');
    Route::post('register', [RegisterController::class, 'store'])
        ->name('register.store');

});

Route::middleware('auth')->group(function () {
    Route::post('logout', [LogoutController::class, 'destroy'])
        ->name('logout');
});
