<?php

use App\Http\Controllers\Settings\PasswordAuthenticationController;

Route::middleware('auth')->group(function (): void {

    Route::get('settings/password-authentication', [PasswordAuthenticationController::class, 'edit'])
        ->name('settings.password.edit');

    Route::put('settings/password-authentication', [PasswordAuthenticationController::class, 'update'])
        ->middleware('throttle:6,1')
        ->name('settings.password.update');

});
