<?php

use App\Models\User;

use function Pest\Laravel\assertAuthenticated;
use function Pest\Laravel\assertGuest;

test('registration screen can be rendered', function () {
    visit(route('register'))
        ->assertSee('Create an account')
        ->assertSee('Enter your details below to create your account')
        ->assertNoConsoleLogs()
        ->assertNoJavaScriptErrors();
});

test('new user can be registered', function () {
    visit(route('register'))
        ->fill('name', 'Marjose Darang')
        ->fill('email', 'marjose@mail.com')
        ->fill('password', 'Admin1$trat0R')
        ->fill('password_confirmation', 'Admin1$trat0R')
        ->submit()
        ->assertPathEndsWith('/verify-email')
        ->assertNoConsoleLogs()
        ->assertNoJavaScriptErrors();

    assertAuthenticated();
});

test('new user cannot be registered when email has already been taken', function () {
    User::factory()->create([
        'name'  => 'Marjose Darang',
        'email' => 'marjose@mail.com',
    ]);

    visit(route('register'))
        ->fill('name', 'Marjose Darang')
        ->fill('email', 'marjose@mail.com')
        ->fill('password', 'Admin1$trat0R')
        ->fill('password_confirmation', 'Admin1$trat0R')
        ->submit()
        ->assertSee('The email has already been taken.')
        ->assertNoConsoleLogs()
        ->assertNoJavaScriptErrors();

    assertGuest();
});

test('new user cannot be registered when password does not match', function () {
    visit(route('register'))
        ->fill('name', 'Marjose Darang')
        ->fill('email', 'marjose@mail.com')
        ->fill('password', 'Admin1$trat0R')
        ->fill('password_confirmation', '$ecR3t')
        ->submit()
        ->assertSee('The password field confirmation does not match.')
        ->assertNoConsoleLogs()
        ->assertNoJavaScriptErrors();

    assertGuest();
});
