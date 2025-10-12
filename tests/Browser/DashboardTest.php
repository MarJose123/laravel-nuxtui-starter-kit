<?php

use App\Models\User;

use function Pest\Laravel\actingAs;

pest()->group('browser');

test('guests are redirected to the login page', function (): void {
    visit(route('dashboard'))
        ->assertUrlIs(route('login'))
        ->assertNoConsoleLogs()
        ->assertNoJavaScriptErrors()
        ->assertSee('Login')
        ->assertSee('Enter your credentials to access your account.');
});

test('authenticated users can visit the dashboard', function (): void {
    actingAs(User::factory()->create());

    visit(route('dashboard'))
        ->assertUrlIs(route('dashboard'))
        ->assertNoConsoleLogs()
        ->assertNoJavaScriptErrors();
});
