<?php

use App\Http\Controllers\Api\Auth\LoginController;

Route::prefix('auth')->group(function (): void {

    Route::post('/login', [LoginController::class, 'store']);

});
