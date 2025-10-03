<?php

namespace App\Actions\Auth;

use App\Models\PersonalAccessToken;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Fluent;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class UpdateApiUserToken
{
    /**
     * @param Request $request  Authenticated request
     * @param string  $refresh  Refresh token
     * @param bool    $remember
     *
     * @return Fluent {'token': string, 'refresh': string, 'expires_at': int, 'expires_in': int}
     */
    public function handle(Request $request, string $refresh, bool $remember = false): Fluent
    {

        /** @var PersonalAccessToken $sanctum */
        $sanctum = PersonalAccessToken::where('refresh_token', $refresh)
            ->where('tokenable_id', $request->user()->id);

        if (! $sanctum->exists()) {
            throw ValidationException::withMessages([
                'refresh' => 'Invalid refresh token',
            ]);
        }

        $freshToken = Str::random(32);
        $plainTextToken = $request->user()->generateTokenString();
        $expiresAt = $remember ? now()->addDay() : now()->addHour();

        $sanctum->refresh_token = $freshToken;
        $sanctum->token = hash('sha256', $plainTextToken);
        $sanctum->expires_at = $expiresAt;
        $sanctum->save();

        return new Fluent([
            'token'      => $sanctum->getKey().'|'.$plainTextToken,
            'refresh'    => $freshToken,
            'expires_at' => $expiresAt->timestamp,
            'expires_in' => (int) ceil(Carbon::now()->diffInSeconds($expiresAt)),
        ]);

    }
}
