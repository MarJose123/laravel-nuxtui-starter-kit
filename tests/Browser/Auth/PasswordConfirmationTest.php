<?php

use App\Models\User;
use Composer\InstalledVersions;

use function Pest\Laravel\actingAs;

pest()->group('browser');

test('confirm password screen can be rendered', function (): void {
    actingAs(User::factory()->create());

    visit(route('password.confirm'))
        ->assertSee('Confirm your password')
        ->assertSee('This is a secure area of the application. Please confirm your password before continuing.')
        ->assertNoConsoleLogs()
        ->assertNoJavaScriptErrors();
});

test('password can be confirmed', function (): void {
    actingAs(User::factory()->create());

    visit(route('password.confirm'))
        ->fill('password', 'password')
        ->submit()
        ->assertUrlIs(route('dashboard'))
        ->assertNoConsoleLogs()
        ->assertNoJavaScriptErrors();
});

test('password is not confirmed with invalid password', function (): void {
    actingAs(User::factory()->create());

    visit(route('password.confirm'))
        ->fill('password', 'wrong-password')
        ->submit()
        ->assertUrlIs(route('password.confirm'))
        ->assertSee(InstalledVersions::isInstalled('laravel/fortify') ? 'The provided password was incorrect.' : 'The provided password is incorrect.')
        ->assertNoConsoleLogs()
        ->assertNoJavaScriptErrors();
});
