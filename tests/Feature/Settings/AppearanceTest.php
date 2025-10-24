<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;

uses(RefreshDatabase::class);

it('can render appearance page', function (): void {
    $user = User::factory()->withoutTwoFactor()->create();

    $this->actingAs($user)
        ->withSession(['auth.password_confirmed_at' => time()])
        ->get(route('settings.appearance.edit'))
        ->assertInertia(fn (Assert $page): Assert => $page
            ->component('settings/Appearance')
        )
        ->assertOk();
});

test('user will have an default theme and colors', function (): void {
    $user = User::factory()->withoutTwoFactor()->create();

    $this->actingAs($user)
        ->get(route('settings.appearance.edit'))
        ->assertInertia(fn (Assert $page): Assert => $page
            ->component('settings/Appearance')
            ->has('theme', fn (Assert $page): Assert => $page
                ->where('mode', 'system')
                ->where('primary', 'green')
                ->where('neutral', 'slate'))
        );
    $this->assertDatabaseEmpty('appearances');
});

it('can update the theme to dark', function (): void {
    $user = User::factory()->withoutTwoFactor()->create();

    $this->actingAs($user)
        ->patch(route('settings.appearance.store'), [
            'mode' => 'dark',
        ])
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('settings.appearance.edit'));

    $this->assertDatabaseHas('appearances', [
        'mode' => 'dark',
    ]);

});

it('can update the theme to light', function (): void {
    $user = User::factory()->withoutTwoFactor()->create();
    $this->actingAs($user)
        ->patch(route('settings.appearance.store'), [
            'mode' => 'light',
        ])
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('settings.appearance.edit'));

    $this->assertDatabaseHas('appearances', [
        'mode' => 'light',
    ]);
});

it('can update the primary color of the user UI', function (): void {
    $user = User::factory()->withoutTwoFactor()->create();

    $this->actingAs($user)
        ->patch(route('settings.appearance.store'), [
            'mode'    => 'dark',
            'primary' => 'sky',
        ])
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('settings.appearance.edit'));

    $this->assertDatabaseHas('appearances', [
        'mode'          => 'dark',
        'primary_color' => 'sky',
    ]);
});

it('can update the secondary color of the user UI', function (): void {
    $user = User::factory()->withoutTwoFactor()->create();

    $this->actingAs($user)
        ->patch(route('settings.appearance.store'), [
            'mode'      => 'dark',
            'secondary' => 'gray',
        ])
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('settings.appearance.edit'));

    $this->assertDatabaseHas('appearances', [
        'mode'            => 'dark',
        'secondary_color' => 'gray',
    ]);
});

it('requires theme to have value on every patch of the record', function (): void {

    $user = User::factory()->withoutTwoFactor()->create();

    $this->actingAs($user)
        ->patch(route('settings.appearance.store'), [
            'secondary' => 'gray',
        ])
        ->assertSessionHasErrors('mode')
        ->assertSessionHasErrors([
            'mode' => 'The mode field is required.',
        ]);
});
