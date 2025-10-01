<?php

use App\Http\Controllers\Settings\TwoFactorAuthenticationController;

Route::middleware('auth')->group(function (): void {

    Route::get('settings/two-factor-authentication', [TwoFactorAuthenticationController::class, 'show'])
        ->name('settings.two-factor.show');

});
