<?php

namespace App\Http\Controllers\Api\Auth;

use App\Actions\Auth\CreateApiUserToken;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Http\Resources\Api\Auth\SessionUserResource;
use App\Http\Resources\Api\Auth\UserTokenResource;
use App\Models\User;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

#[Group(
    name: 'Authentication',
    description: 'Authentication related endpoints',
)]
class LoginController extends Controller
{
    public function __construct(private readonly CreateApiUserToken $userToken) {}

    /**
     * Login User
     *
     * Authenticate a user and return a token. If the user has enabled two-factor authentication,
     * then it will be ignored, and the user will be authenticated without two-factor authentication.
     *
     * @unauthenticated
     *
     * @param LoginRequest $request
     *
     * @return JsonResponse
     */
    public function store(LoginRequest $request): JsonResponse
    {
        $request->ensureIsNotRateLimited();

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            RateLimiter::hit($request->throttleKey());

            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        RateLimiter::clear($request->throttleKey());

        $token = $this->userToken->handle(
            user: $user,
            request: $request,
            deviceName: $request->device_name,
            remember: $request->boolean('remember'),
        );

        /**
         * JSON response with the access token and user information.
         */
        return new JsonResponse(data: [
            /**
             * Access token to be used for authenticated requests.
             */
            'token' => UserTokenResource::make($token),
            /**
             * User information of the authenticated user.
             */
            'user' => SessionUserResource::make($user),
        ], status: Response::HTTP_CREATED);

    }
}
