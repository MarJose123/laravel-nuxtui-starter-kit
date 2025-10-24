<?php

use App\Http\Controllers\Api\Users\CurrentUserController;

Route::middleware('auth:users-api')->prefix('/users')->group(function (): void {

    Route::get('/me', [CurrentUserController::class, 'me']);

});
