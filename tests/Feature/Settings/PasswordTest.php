<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('password update page is rendered', function (): void {
    $user = User::factory()->create();

    $this
        ->actingAs($user)
        ->withSession(['auth.password_confirmed_at' => Date::now()->unix()])
        ->get(route('settings.password.edit'))
        ->assertStatus(200);
});

test('password can be updated', function (): void {
    $user = User::factory()->create();

    $this
        ->actingAs($user)
        ->withSession(['auth.password_confirmed_at' => Date::now()->unix()])
        ->from(route('settings.password.edit'))
        ->put(route('settings.password.update'), [
            'current_password'      => 'password',
            'password'              => 'Admin1$trat0R',
            'password_confirmation' => 'Admin1$trat0R',
        ])
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('settings.password.edit'));
});

test('correct password must be provided to update the password', function (): void {
    $user = User::factory()->create();
    $this
        ->actingAs($user)
        ->withSession(['auth.password_confirmed_at' => Date::now()->unix()])
        ->from(route('settings.password.edit'))
        ->put(route('settings.password.update'), [
            'current_password'      => 'wrong-password',
            'password'              => 'Admin1$trat0R',
            'password_confirmation' => 'Admin1$trat0R',
        ])
        ->assertSessionHasErrors(['current_password'])
        ->assertRedirect(route('settings.password.edit'));
});
