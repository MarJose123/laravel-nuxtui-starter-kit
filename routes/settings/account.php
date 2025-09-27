<?php

use App\Http\Controllers\Settings\AccountController;

Route::middleware('auth')->group(function (): void {
    Route::redirect('/settings', '/settings/account');

    Route::get('settings/account', [AccountController::class, 'edit'])->name('settings.account.edit');
    Route::patch('settings/account', [AccountController::class, 'update'])->name('settings.account.update');
    Route::delete('settings/account', [AccountController::class, 'destroy'])->name('settings.account.destroy');

});
