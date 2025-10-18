<?php

use App\Models\User;
use Illuminate\Support\Facades\RateLimiter;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertAuthenticated;
use function Pest\Laravel\assertGuest;

pest()->group('browser');

test('login screen can be rendered', function (): void {
    visit(route('login'))
        ->assertNoConsoleLogs()
        ->assertNoJavaScriptErrors()
        ->assertSee('Login')
        ->assertSee('Enter your credentials to access your account.');
});

test('users can authenticate using the login screen', function (): void {
    $user = User::factory()->withoutTwoFactor()->create();

    visit(route('login'))
        ->fill('email', $user->email)
        ->fill('password', 'password')
        ->submit()
        ->assertUrlIs(route('dashboard'))
        ->assertNoConsoleLogs()
        ->assertNoJavaScriptErrors();

    assertAuthenticated();
});

test('users can not authenticate with invalid password', function (): void {
    $user = User::factory()->create();

    visit(route('login'))
        ->fill('email', $user->email)
        ->fill('password', 'wrong-password')
        ->submit()
        ->assertUrlIs(route('login'))
        ->assertSee('These credentials do not match our records.')
        ->assertNoConsoleLogs()
        ->assertNoJavaScriptErrors();

    assertGuest();
});

test('users can logout', function (): void {
    $user = User::factory()->create();

    actingAs($user);

    visit(route('dashboard'))
        ->click('#dashboard-sidebar-v-0 > div.shrink-0.flex.items-center.gap-1\.5.px-4.py-2.border-t.border-default > div > button')
        ->assertUrlIs(route('home'))
        ->assertNoConsoleLogs()
        ->assertNoJavaScriptErrors();

    assertGuest();
});

test('users are rate limited', function (): void {
    $user = User::factory()->create();

    RateLimiter::increment(implode('|', [$user->email, '127.0.0.1']), amount: 10);

    visit(route('login'))
        ->fill('email', $user->email)
        ->fill('password', 'wrong-password')
        ->submit()
        ->assertUrlIs(route('login'))
        ->assertSee('Too many login attempts. Please try again in')
        ->assertNoConsoleLogs()
        ->assertNoJavaScriptErrors();
});
