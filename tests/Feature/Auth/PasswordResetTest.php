<?php

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;

uses(RefreshDatabase::class);

pest()->group('feature');

test('reset password link screen can be rendered', function (): void {
    $this->get(route('password.request'))
        ->assertStatus(200);
});

test('reset password link can be requested', function (): void {
    Notification::fake();

    $user = User::factory()->create();

    $this->post(route('password.email'), ['email' => $user->email]);

    Notification::assertSentTo($user, ResetPassword::class);
});

test('reset password screen can be rendered', function (): void {
    Notification::fake();

    $user = User::factory()->create();

    $this->post(route('password.email'), ['email' => $user->email]);

    Notification::assertSentTo($user, ResetPassword::class, function ($notification) {
        $this->get(route('password.reset', $notification->token))
            ->assertStatus(200);

        return true;
    });
});

test('password can be reset with valid token', function (): void {

    Notification::fake();

    $user = User::factory()->create();

    $this->post(route('password.email'), ['email' => $user->email]);

    Notification::assertSentTo($user, ResetPassword::class, function ($notification) use ($user) {
        $this->post(route('password.store'), [
            'token'                 => $notification->token,
            'email'                 => $user->email,
            'password'              => 'Admin1$trat0R',
            'password_confirmation' => 'Admin1$trat0R',
        ])
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('login'));

        return true;
    });
});

test('password can not be reset with invalid token', function (): void {
    $user = User::factory()->create();

    $response = $this->post(route('password.store'), [
        'token'                 => 'invalid-token',
        'email'                 => $user->email,
        'password'              => 'Admin1$trat0R',
        'password_confirmation' => 'Admin1$trat0R',
    ]);

    $response->assertSessionHasErrors('email');
});
