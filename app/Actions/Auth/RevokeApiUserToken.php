<?php

namespace App\Actions\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;

class RevokeApiUserToken
{
    /**
     * Delete user token
     *
     * @param Request $request
     *
     * @return void
     */
    public function handle(Request $request): void
    {
        $request->validate([
            'token'   => is_array($request->token) ? ['required', 'array'] : ['required', 'integer'],
            'token.*' => ['required', 'integer'],
        ]);
        $token = $request->token;

        /** @var User $user */
        $user = $request->user();

        /** @var PersonalAccessToken|null $sanctum */
        $sanctum = $user->currentAccessToken();

        // sanitize token ids and don't allow the current user token to delete itself
        if (is_array($token) && $sanctum && in_array($sanctum->id, $token)) {
            unset($token[$sanctum->id]);
        }

        if (is_string($token) && $sanctum && $sanctum->id === $token) {
            return;
        }

        $user->tokens()->whereIn('id', is_array($token) ? $token : [$token])->delete();
    }
}
