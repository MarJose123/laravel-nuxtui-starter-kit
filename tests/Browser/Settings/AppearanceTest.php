<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\actingAs;

uses(RefreshDatabase::class);

test('appearance page is displayed', function (): void {
    actingAs($user = User::factory()->create());

    visit(route('settings.appearance.edit'))
        ->assertSee('Appearance settings')
        ->assertSee("Update your account's appearance settings")
        ->assertSee('Theme')
        ->assertSee('Primary Color')
        ->assertSee('Neutral Color')
        ->assertNoConsoleLogs()
        ->assertNoJavaScriptErrors();
});

test('can select theme to light', function (): void {
    actingAs($user = User::factory()->create());

    visit(route('settings.appearance.edit'))
        ->assertSee('Appearance settings')
        ->assertSee("Update your account's appearance settings")
        ->assertSee('Theme')
        ->click('@theme-light')
        ->assertUrlIs(route('settings.appearance.edit'))
        ->refresh()
        ->assertAttributeContains('@theme-light', 'class', 'bg-elevated')
        ->assertNoConsoleLogs()
        ->assertNoJavaScriptErrors();
});

test('can select theme to dark', function (): void {
    actingAs($user = User::factory()->create());

    visit(route('settings.appearance.edit'))
        ->assertSee('Appearance settings')
        ->assertSee("Update your account's appearance settings")
        ->assertSee('Theme')
        ->click('@theme-dark')
        ->assertUrlIs(route('settings.appearance.edit'))
        ->refresh()
        ->assertAttributeContains('@theme-dark', 'class', 'bg-elevated')
        ->assertNoConsoleLogs()
        ->assertNoJavaScriptErrors();
});

test('can select theme to system', function (): void {
    actingAs($user = User::factory()->create());

    visit(route('settings.appearance.edit'))
        ->assertSee('Appearance settings')
        ->assertSee("Update your account's appearance settings")
        ->assertSee('Theme')
        ->click('@theme-system')
        ->assertUrlIs(route('settings.appearance.edit'))
        ->refresh()
        ->assertAttributeContains('@theme-system', 'class', 'bg-elevated')
        ->assertNoConsoleLogs()
        ->assertNoJavaScriptErrors();
});

test('can select primary color', function (): void {
    actingAs($user = User::factory()->create());

    visit(route('settings.appearance.edit'))
        ->assertSee('Appearance settings')
        ->assertSee("Update your account's appearance settings")
        ->assertSee('Theme')
        ->click('@primary-color-red')
        ->assertUrlIs(route('settings.appearance.edit'))
        ->refresh()
        ->assertAttributeContains('@primary-color-red', 'class', 'bg-elevated')
        ->assertNoConsoleLogs()
        ->assertNoJavaScriptErrors();
});

test('can select secondary color', function (): void {
    actingAs($user = User::factory()->create());

    visit(route('settings.appearance.edit'))
        ->assertSee('Appearance settings')
        ->assertSee("Update your account's appearance settings")
        ->assertSee('Theme')
        ->click('@secondary-color-zinc')
        ->assertUrlIs(route('settings.appearance.edit'))
        ->refresh()
        ->assertAttributeContains('@secondary-color-zinc', 'class', 'bg-elevated')
        ->assertNoConsoleLogs()
        ->assertNoJavaScriptErrors();
});
