<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', fn (Request $request) => $request->user('users-api'))->middleware('auth:users-api');

require __DIR__.'/api/auth/auth.php';
require __DIR__.'/api/profile/user.php';
