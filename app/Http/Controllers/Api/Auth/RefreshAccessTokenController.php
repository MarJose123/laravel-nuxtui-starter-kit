<?php

namespace App\Http\Controllers\Api\Auth;

use App\Actions\Auth\UpdateApiUserToken;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\RefreshTokenRequest;
use App\Http\Resources\Api\Auth\UserTokenResource;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

#[Group(
    name: 'Authentication',
    description: 'Authentication related endpoints',
)]
class RefreshAccessTokenController extends Controller
{
    public function __construct(private readonly UpdateApiUserToken $userToken) {}

    /**
     * Refresh Token
     *
     * Refresh the access token for an authenticated user.
     *
     * @weight 3
     *
     * @param RefreshTokenRequest $request
     *
     * @return JsonResponse
     */
    public function store(RefreshTokenRequest $request)
    {
        $token = $this->userToken->handle($request);

        /**
         * JSON response with the access token and user information.
         */
        return new JsonResponse(data: [
            /**
             * Access token to be used for authenticated requests.
             */
            'token' => UserTokenResource::make($token),
        ], status: Response::HTTP_CREATED);

    }
}
