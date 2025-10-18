<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Fortify\Features;

uses(RefreshDatabase::class);

pest()->group('feature');

test('login screen can be rendered', function (): void {
    $this->get(route('login'))
        ->assertStatus(200);
});

test('users can authenticate using the login screen', function (): void {
    $user = User::factory()->withoutTwoFactor()->create();

    $this->post(route('login.store'), [
        'email'    => $user->email,
        'password' => 'password',
    ])
        ->assertRedirect(route('dashboard', absolute: false));

    $this->assertAuthenticated();
});

test('user with two factor enabled are redirected to two factor challenge', function (): void {
    if (! Features::canManageTwoFactorAuthentication()) {
        $this->markTestSkipped('Two-factor authentication is not enabled.');
    }

    Features::twoFactorAuthentication([
        'confirm'         => true,
        'confirmPassword' => true,
    ]);

    $user = User::factory()->create();

    $user->forceFill([
        'two_factor_secret'         => encrypt('test-secret'),
        'two_factor_recovery_codes' => encrypt(json_encode(['code1', 'code2'])),
        'two_factor_confirmed_at'   => now(),
    ])->save();

    $response = $this->post(route('login'), [
        'email'    => $user->email,
        'password' => 'password',
    ])
        ->assertRedirect(route('two-factor.login'))
        ->assertSessionHas('login.id', $user->id);
    $this->assertGuest();
});

test('users can not authenticate with invalid password', function (): void {
    $user = User::factory()->create();
    $this->post(route('login.store'), [
        'email'    => $user->email,
        'password' => 'wrong-password',
    ]);

    $this->assertGuest();
});

test('user can logout', function (): void {
    $user = User::factory()->create();

    $this->actingAs($user)->post(route('logout'))
        ->assertRedirect(route('home'));

    $this->assertGuest();

});

test('login is rate limited', function (): void {
    $user = User::factory()->create();

    RateLimiter::increment(implode('|', [$user->email, '127.0.0.1']), amount: 10);

    $this->post(route('login.store'), [
        'email'    => $user->email,
        'password' => 'wrong-password',
    ])
        ->assertSessionHasErrors('email');

    $errors = session('errors');

    $this->assertStringContainsString('Too many login attempts', $errors->first('email'));
});
