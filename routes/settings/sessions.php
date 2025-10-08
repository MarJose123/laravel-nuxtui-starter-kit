<?php

use App\Http\Controllers\Settings\SessionController;

Route::middleware('auth')->group(function (): void {

    Route::get('settings/sessions', [SessionController::class, 'edit'])
        ->name('settings.sessions.edit');

    Route::delete('settings/sessions', [SessionController::class, 'destroy'])
        ->name('settings.sessions.destroy');

});
