<?php

namespace App\Actions\Auth;

use App\Models\PersonalAccessToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Fluent;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class UpdateApiUserToken
{
    /**
     * @param Request $request Authenticated request
     *
     * @return Fluent {'token': string, 'refresh': string, 'expires_at': int, 'expires_in': int}
     */
    public function handle(Request $request): Fluent
    {

        /** @var PersonalAccessToken $sanctum */
        $sanctum = PersonalAccessToken::query()->where('refresh_token', $request->refresh_token)
            ->where('tokenable_id', $request->user()->id)->first();

        if (! $sanctum || ! $sanctum->exists()) {
            throw ValidationException::withMessages([
                'refresh_token' => 'Invalid refresh token',
            ]);
        }

        $freshToken = Str::random(32);
        $plainTextToken = $request->user()->generateTokenString();
        $expiresAt = $request->boolean('remember') ? now()->addDay() : now()->addHour();

        $sanctum->refresh_token = $freshToken;
        $sanctum->token = hash('sha256', $plainTextToken);
        $sanctum->expires_at = $expiresAt;
        $sanctum->save();

        return new Fluent([
            'token'      => $sanctum->getKey().'|'.$plainTextToken,
            'refresh'    => $freshToken,
            'expires_at' => $expiresAt->timestamp,
            'expires_in' => (int) ceil(Date::now()->diffInSeconds($expiresAt)),
        ]);

    }
}
