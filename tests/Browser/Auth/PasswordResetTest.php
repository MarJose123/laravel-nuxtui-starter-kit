<?php

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;

pest()->group('browser');

test('reset password link screen can be rendered', function (): void {
    visit(route('password.request'))
        ->assertSee('Forgot password')
        ->assertSee('Enter your email to receive a password reset link')
        ->assertNoConsoleLogs()
        ->assertNoJavaScriptErrors();
});

test('test reset password link can be requested', function (): void {
    $user = User::factory()->create();

    Notification::fake();

    visit(route('password.request'))
        ->assertSee('Enter your email to receive a password reset link')
        ->fill('email', $user->email)
        ->submit()
        ->assertSee('Password reset link sent.')
        ->assertNoConsoleLogs()
        ->assertNoJavaScriptErrors();

    Notification::assertSentTo($user, ResetPassword::class);
});

test('reset password screen can be rendered', function (): void {
    $user = User::factory()->create();

    Notification::fake();

    Password::sendResetLink(['email' => $user->email]);

    Notification::assertSentTo($user, ResetPassword::class, function ($notification) {
        visit(route('password.reset', $notification->token))
            ->assertNoConsoleLogs()
            ->assertNoJavaScriptErrors();

        return true;
    });
});

test('password can be reset with valid token', function (): void {
    $user = User::factory()->create();

    Notification::fake();

    Password::sendResetLink(['email' => $user->email]);

    Notification::assertSentTo($user, ResetPassword::class, function ($notification) use ($user) {
        visit(route('password.reset', ['token' => $notification->token, 'email' => $user->email]))
            ->fill('password', 'Admin1$trat0R')
            ->fill('password_confirmation', 'Admin1$trat0R')
            ->assertValue('email', $user->email)
            ->submit()
            ->assertUrlIs(route('login'))
            ->assertSee('Your password has been reset.');

        return true;
    });
});
