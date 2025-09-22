<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\SignUpController;

Route::middleware('guest')->group(function () {

    Route::get('login', [LoginController::class, 'create'])
        ->name('login');

    Route::post('login', [LoginController::class, 'store'])
        ->name('login.store');

    Route::get('signup', [SignUpController::class, 'create'])
        ->name('signup');
    Route::post('signup', [SignUpController::class, 'store'])
        ->name('signup.store');

});

Route::middleware('auth')->group(function () {
    Route::post('logout', [LogoutController::class, 'destroy'])
        ->name('logout');
});
