<?php

use App\Models\User;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Hash;

use function Pest\Laravel\actingAs;

pest()->group('browser');

test('password update page is displayed', function (): void {
    actingAs(User::factory()->create())
        ->withSession(['auth.password_confirmed_at' => Date::now()->unix()]);

    visit(route('settings.password.edit'))
        ->assertSee('Update password')
        ->assertSee('Ensure your account is using a long, random password to stay secure')
        ->assertNoConsoleLogs()
        ->assertNoJavaScriptErrors();
});

test('password can be updated', function (): void {
    actingAs($user = User::factory()->create())
        ->withSession(['auth.password_confirmed_at' => Date::now()->unix()]);

    visit(route('settings.password.edit'))
        ->fill('current_password', 'password')
        ->fill('password', 'Admin1$trat0R1')
        ->fill('password_confirmation', 'Admin1$trat0R1')
        ->submit()
        ->assertSee('Saved')
        ->assertUrlIs(route('settings.password.edit'))
        ->assertNoConsoleLogs()
        ->assertNoJavaScriptErrors();

    expect(Hash::check('Admin1$trat0R1', $user->refresh()->password))->toBeTrue();
});

test('correct password must be provided to update password', function (): void {
    actingAs($user = User::factory()->create())
        ->withSession(['auth.password_confirmed_at' => Date::now()->unix()]);

    visit(route('settings.password.edit'))
        ->fill('current_password', 'wrong-password')
        ->fill('password', 'new-password')
        ->fill('password_confirmation', 'new-password')
        ->submit()
        ->assertSee('The password is incorrect.')
        ->assertUrlIs(route('settings.password.edit'))
        ->assertNoConsoleLogs()
        ->assertNoJavaScriptErrors();

    expect(Hash::check('wrong-password', $user->refresh()->password))->toBeFalse();
});
