<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

test('users can authenticate and receive token once authenticated', function (): void {
    $user = User::factory()->withoutTwoFactor()->create();

    $this->withHeaders([
        'user-agent' => 'Laravel Testing',
    ])
        ->postJson('api/auth/login', [
            'email'       => $user->email,
            'password'    => 'password',
            'device_name' => 'Laravel Testing',
            'remember'    => true,
        ])
        ->assertStatus(201)
        ->assertJsonStructure([
            'token',
            'user',
        ])
        ->assertJson(fn (AssertableJson $json): AssertableJson => $json->hasAll(['token', 'user']));

    $this->assertDatabaseCount('personal_access_tokens', 1);

    $this->assertDatabaseHas('personal_access_tokens', [
        'tokenable_id' => $user->id,
    ]);

});

test('users can not authenticate with invalid password', function (): void {
    $user = User::factory()->withoutTwoFactor()->create();

    $this->withHeaders([
        'user-agent' => 'Laravel Testing',
    ])
        ->postJson('api/auth/login', [
            'email'       => $user->email,
            'password'    => 'invalid-password',
            'device_name' => 'Laravel Testing',
            'remember'    => true,
        ])
        ->assertStatus(422)
        ->assertJsonValidationErrors(['email']);
});

test('user can logout', function (): void {
    $user = User::factory()->create();

    Sanctum::actingAs($user, ['*'], 'users-api');

    $this->postJson('api/auth/logout')
        ->assertJsonStructure([
            'message',
        ])
        ->assertStatus(200);

});

test('user token is delete upon user successful logout', function (): void {
    $user = User::factory()->create();

    $resp = $this->withHeaders([
        'user-agent' => 'Laravel Testing',
    ])
        ->postJson('api/auth/login', [
            'email'       => $user->email,
            'password'    => 'password',
            'device_name' => 'Laravel Testing',
            'remember'    => true,
        ]);

    $resp->assertStatus(201)
        ->assertJsonStructure([
            'token',
            'user',
        ])
        ->assertJson(fn (AssertableJson $json): AssertableJson => $json->hasAll(['token', 'user']));

    $this->assertDatabaseCount('personal_access_tokens', 1);

    $this->assertDatabaseHas('personal_access_tokens', [
        'tokenable_id' => $user->id,
    ]);

    $this->withToken($resp->json('token')['access_token'])
        ->postJson('api/auth/logout')
        ->assertJsonStructure([
            'message',
        ])
        ->assertStatus(200);

    $this->assertDatabaseMissing('personal_access_tokens', [
        'tokenable_id' => $user->id,
    ]);

});

test('login is rate limited', function (): void {
    $user = User::factory()->create();

    RateLimiter::increment(implode('|', [$user->email, 'api', '127.0.0.1']), amount: 10);

    $resp = $this->withHeaders([
        'user-agent' => 'Laravel Testing',
    ])
        ->postJson('api/auth/login', [
            'email'       => $user->email,
            'password'    => 'password',
            'device_name' => 'Laravel Testing',
            'remember'    => true,
        ])
        ->assertJsonValidationErrors('email');

    $errors = $resp->json('errors');

    $this->assertStringContainsString('Too many login attempts', Arr::get($errors, 'email.0'));
});
