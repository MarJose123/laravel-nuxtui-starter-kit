<?php

use App\Http\Controllers\Api\Users\CurrentUserController;

Route::middleware('auth:sanctum')->prefix('/users')->group(function (): void {

    Route::get('/me', [CurrentUserController::class, 'me']);

});
