<?php

use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertAuthenticated;
use function Pest\Laravel\assertGuest;

pest()->group('browser');

test('profile page is displayed', function (): void {
    actingAs($user = User::factory()->create());

    visit(route('settings.account.edit'))
        ->assertSee('Update your name and email address')
        ->assertValue('name', $user->name)
        ->assertValue('email', $user->email)
        ->assertNoConsoleLogs()
        ->assertNoJavaScriptErrors();
});

test('profile information can be updated', function (): void {
    actingAs($user = User::factory()->create());

    visit(route('settings.account.edit'))
        ->assertSee('Update your name and email address')
        ->fill('name', 'Test User')
        ->fill('email', 'test@example.com')
        ->submit()
        ->assertSee('Saved')
        ->assertUrlIs(route('settings.account.edit'))
        ->assertNoConsoleLogs()
        ->assertNoJavaScriptErrors();

    $user->refresh();

    expect($user->name)->toBe('Test User')
        ->and($user->email)->toBe('test@example.com')
        ->and($user->email_verified_at)->toBeNull();
});

test('email verification status is unchanged when the email address is unchanged', function (): void {
    actingAs($user = User::factory()->create());

    visit(route('settings.account.edit'))
        ->assertSee('Update your name and email address')
        ->fill('name', 'Test User')
        ->fill('email', $user->email)
        ->submit()
        ->assertSee('Saved')
        ->assertUrlIs(route('settings.account.edit'))
        ->assertNoConsoleLogs()
        ->assertNoJavaScriptErrors();

    expect($user->refresh()->email_verified_at)->not->toBeNull();
});

test('user can delete their account', function (): void {
    actingAs($user = User::factory()->create());

    visit(route('settings.account.edit'))
        ->press('@delete-account')
        ->assertSee('Are you sure you want to delete your account?')
        ->fill('password', 'password')
        ->press('@confirm-delete-user-button')
        ->assertUrlIs(route('home'))
        ->assertNoConsoleLogs()
        ->assertNoJavaScriptErrors();

    assertGuest();
    expect($user->fresh())->toBeNull();
});

test('correct password must be provided to delete account', function (): void {
    actingAs($user = User::factory()->create());

    visit(route('settings.account.edit'))
        ->press('@delete-account')
        ->assertSee('Are you sure you want to delete your account?')
        ->fill('password', 'wrong-password')
        ->press('@confirm-delete-user-button')
        ->assertUrlIs(route('settings.account.edit'))
        ->assertNoConsoleLogs()
        ->assertNoJavaScriptErrors();

    assertAuthenticated();
    expect($user->fresh())->not->toBeNull();
});
