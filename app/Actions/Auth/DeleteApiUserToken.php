<?php

namespace App\Actions\Auth;

use Illuminate\Http\Request;

class DeleteApiUserToken
{
    /**
     * Delete user token
     *
     * @param Request      $request
     * @param string|array $token   Token ID or array of token IDs
     *
     * @return void
     */
    public function handle(Request $request, string|array $token): void
    {
        $request->validate([
            'token'   => ['required', 'string'],
            'token.*' => ['required', 'string', 'exists:personal_access_tokens,id'],
        ]);

        // sanitize token ids, and don't allow the current user token to delete itself
        if (is_array($token)) {
            unset($token[$request->user()->currentAccessToken()->id]);
        } else {
            if ($token === $request->user()->currentAccessToken()->id) {
                return;
            }
        }

        $request->user()->tokens()->whereIn('id', is_array($token) ? $token : [$token])->delete();
    }
}
