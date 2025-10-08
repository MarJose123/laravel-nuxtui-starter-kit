<?php

namespace App\Actions\Auth;

use Illuminate\Http\Request;

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

        // sanitize token ids, and don't allow the current user token to delete itself
        if (is_array($token) && in_array($request->user()->currentAccessToken()?->id, $token)) {
            unset($token[$request->user()->currentAccessToken()?->id]);
        } else {
            if ($token === $request->user()?->currentAccessToken()?->id) {
                return;
            }
        }

        $request->user()->tokens()->whereIn('id', is_array($token) ? $token : [$token])->delete();
    }
}
