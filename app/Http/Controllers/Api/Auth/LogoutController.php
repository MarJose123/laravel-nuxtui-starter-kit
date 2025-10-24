<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\PersonalAccessToken;
use App\Models\User;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

#[Group(
    name: 'Authentication',
    description: 'Authentication related endpoints',
)]

class LogoutController extends Controller
{
    /**
     * Logout User
     *
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function destroy(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user('users-api');

        /** @var PersonalAccessToken $currentToken */
        $currentToken = $user->currentAccessToken();
        $currentToken->delete();

        /**
         * Successful logout response
         */
        return new JsonResponse([
            'message' => 'Logged out successfully',
        ]);
    }
}
