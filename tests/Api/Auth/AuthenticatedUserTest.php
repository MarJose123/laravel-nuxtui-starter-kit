<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

test('can get the data of the authenticated request', function (): void {
    $user = User::factory()->create();

    Sanctum::actingAs($user, ['*'], 'users-api');

    $resp = $this->getJson('api/users/me');

    $resp->assertJsonStructure([
        'id',
        'name',
        'email',
        'email_verified_at',
    ])
        ->assertStatus(200)
        ->assertJson([
            'id'    => $user->id,
            'name'  => $user->name,
            'email' => $user->email,
        ]);

    expect($resp->json())->toMatchArray(collect($user)->except([
        'two_factor_secret',
        'two_factor_recovery_codes',
        'two_factor_confirmed_at',
    ]));

});
