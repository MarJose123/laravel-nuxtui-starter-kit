<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;

uses(RefreshDatabase::class);
pest()->group('feature');
test('confirm password screen can be rendered', function (): void {
    $user = User::factory()->create();

    $this->actingAs($user)->get(route('password.confirm'))
        ->assertStatus(200)
        ->assertInertia(fn (Assert $page): Assert => $page
            ->component('auth/ConfirmPasswordPrompt')
        );

});

test('password confirmation requires authentication', function (): void {
    $response = $this->get(route('password.confirm'))
        ->assertRedirect(route('login'));
});
