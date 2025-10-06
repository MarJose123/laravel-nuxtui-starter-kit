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
            'token'   => ['required', 'string'],
            'token.*' => ['required', 'string', 'exists:personal_access_tokens,id'],
        ]);
        $token = $request->token;

        // sanitize token ids, and don't allow the current user token to delete itself
        if (is_array($token) && in_array($request->user()->currentAccessToken()->id, $token)) {
            unset($token[$request->user()->currentAccessToken()->id]);
        } else {
            if ($token === $request->user()->currentAccessToken()->id) {
                return;
            }
        }

        $request->user()->tokens()->whereIn('id', is_array($token) ? $token : [$token])->delete();
    }
}
