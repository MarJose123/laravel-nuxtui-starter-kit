<?php

namespace App\Http\Controllers\Api\Auth;

use App\Actions\Auth\CreateApiUserToken;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Models\User;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

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
     * Authenticate a user and return a token.
     *
     * @unauthenticated
     *
     * @param LoginRequest $request
     *
     * @return JsonResponse
     *
     * @response array{ token: string, refresh: string, expires_at: int, expires_in: int}
     */
    public function store(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $token = $this->userToken->handle(
            user: $user,
            request: $request,
            deviceName: $request->device_name,
            remember: $request->boolean('remember'),
        );

        return response()->json($token);

    }
}
