<?php

use App\Models\User;

use function Pest\Laravel\actingAs;

pest()->group('browser');

test('it can ask for password confirmation on visiting the update password page', function (): void {
    actingAs(User::factory()->create());

    visit(route('settings.password.edit'))
        ->assertUrlIs(route('password.confirm'))
        ->assertSee('Confirm your password')
        ->assertSee('This is a secure area of the application. Please confirm your password before continuing.')
        ->fill('password', 'password')
        ->submit()
        ->assertUrlIs(route('settings.password.edit'))
        ->assertSee('Update password')
        ->assertSee('Ensure your account is using a long, random password to stay secure')
        ->assertNoConsoleLogs()
        ->assertNoJavaScriptErrors();
});

test('it can ask for password confirmation on visiting the 2FA page', function (): void {
    actingAs(User::factory()->create());

    visit(route('settings.two-factor.show'))
        ->assertUrlIs(route('password.confirm'))
        ->assertSee('Confirm your password')
        ->assertSee('This is a secure area of the application. Please confirm your password before continuing.')
        ->fill('password', 'password')
        ->submit()
        ->assertUrlIs(route('settings.two-factor.show'))
        ->assertSee('Two-Factor Authentication')
        ->assertSee('Manage your two-factor authentication settings')
        ->assertNoConsoleLogs()
        ->assertNoJavaScriptErrors();
});
