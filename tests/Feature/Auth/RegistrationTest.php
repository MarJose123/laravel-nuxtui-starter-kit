<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('registration screen can be rendered', function (): void {
    $this->get(route('register'))
        ->assertStatus(200);
});

test('new user can register', function (): void {
    $this->post(route('register.store'), [
        'name'                  => 'Test User',
        'email'                 => 'test@example.com',
        'password'              => 'Admin1$trat0R',
        'password_confirmation' => 'Admin1$trat0R',
    ])
        ->assertRedirect(route('dashboard', absolute: false));

    $this->assertAuthenticated();
});
