<?php

namespace App\Actions\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Fluent;
use Illuminate\Support\Str;

class CreateApiUserToken
{
    /**
     * @param User    $user
     * @param Request $request
     * @param string  $deviceName
     * @param bool    $remember
     *
     * @return Fluent {'token', 'refresh', 'expires_at', 'expires_in'}
     */
    public function handle(User $user, Request $request, string $deviceName, bool $remember = false): Fluent
    {
        $freshToken = Str::random(32);
        $expiresAt = $remember ? now()->addDay() : now()->addHour();

        $sanctumToken = $user->createToken(
            name: $deviceName,
            abilities: ['*'],
            expiresAt: $expiresAt,
        );

        $sanctumToken->accessToken->refresh_token = $freshToken;
        $sanctumToken->accessToken->ip_address = $request->ip();
        $sanctumToken->accessToken->user_agent = $request->userAgent();
        $sanctumToken->accessToken->save();

        return new Fluent([
            'token'      => $sanctumToken->plainTextToken,
            'refresh'    => $freshToken,
            'expires_at' => $expiresAt->timestamp,
            'expires_in' => (int) ceil(Date::now()->diffInSeconds($expiresAt)),
        ]);

    }
}
