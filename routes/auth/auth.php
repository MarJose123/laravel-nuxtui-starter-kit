<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Auth\RegisterUserController;

Route::middleware('guest')->group(function (): void {

    Route::get('login', [LoginController::class, 'create'])
        ->name('login');

    Route::post('login', [LoginController::class, 'store'])
        ->name('login.store');

    Route::get('register', [RegisterUserController::class, 'create'])
        ->name('register');

    Route::post('register', [RegisterUserController::class, 'store'])
        ->name('register.store');

    Route::get('forgot-password', [PasswordResetController::class, 'create'])
        ->name('password.request');

    Route::post('forgot-password', [PasswordResetController::class, 'store'])
        ->middleware('throttle:4,1')
        ->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset');

    Route::post('reset-password', [NewPasswordController::class, 'store'])
        ->name('password.store');

});

Route::middleware('auth')->group(function (): void {
    Route::post('logout', [LogoutController::class, 'destroy'])
        ->name('logout');
});
