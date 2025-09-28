<?php

use App\Http\Controllers\Settings\AppearanceController;

Route::middleware('auth')->group(function (): void {

    Route::get('settings/appearance', [AppearanceController::class, 'edit'])->name('settings.appearance.edit');
    Route::patch('settings/appearance', [AppearanceController::class, 'store'])->name('settings.appearance.store');

});
