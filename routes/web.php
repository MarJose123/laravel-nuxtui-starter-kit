<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', fn () => Inertia::render('Welcome'))->name('home');

Route::get('/dashboard', fn () => Inertia::render('Dashboard'))->name('dashboard')->middleware(['auth', 'verified']);

require __DIR__.'/auth/auth.php';
require __DIR__.'/settings/account.php';
require __DIR__.'/settings/appearance.php';
require __DIR__.'/settings/password.php';
require __DIR__.'/settings/2fa.php';
require __DIR__.'/settings/sessions.php';
