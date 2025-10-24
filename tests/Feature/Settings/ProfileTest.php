<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('it can render profile page', function (): void {
    $user = User::factory()->create();

    $this
        ->actingAs($user)
        ->get(route('settings.account.edit'))
        ->assertOk();
});

test('it can update profile information', function (): void {
    $user = User::factory()->create();

    $this
        ->actingAs($user)
        ->patch(route('settings.account.update'), [
            'name'  => 'Test User',
            'email' => 'test@example.com',
        ])
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('settings.account.edit'));

    $user->refresh();

    $this->assertSame('Test User', $user->name);
    $this->assertSame('test@example.com', $user->email);
    $this->assertNull($user->email_verified_at);
});

test('it dont update the email and the email verification status when updating the profile name', function (): void {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->patch(route('settings.account.update'), [
            'name'  => 'Test User',
            'email' => $user->email,
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('settings.account.edit'));

    $this->assertNotNull($user->refresh()->email_verified_at);
});

test('it can delete their own account', function (): void {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->delete(route('settings.account.destroy'), [
            'current_password' => 'password',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('home'));

    $this->assertGuest();
    $this->assertNull($user->fresh());
});

test('it requires password confirmation when deleting their own account', function (): void {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->from(route('settings.account.edit'))
        ->delete(route('settings.account.destroy'), [
            'current_password' => 'wrong-password',
        ]);

    $response
        ->assertSessionHasErrors('current_password')
        ->assertRedirect(route('settings.account.edit'));

    $this->assertNotNull($user->fresh());
});
