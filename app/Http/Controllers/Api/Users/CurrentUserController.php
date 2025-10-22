<?php

namespace App\Http\Controllers\Api\Users;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\Auth\SessionUserResource;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

#[Group(
    name: 'Users',
    description: 'Users related endpoints',
)]
class CurrentUserController extends Controller
{
    /**
     * Authenticated User
     *
     * Show the current authenticated user data
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function me(Request $request)
    {
        return new JsonResponse(data: SessionUserResource::make($request->user('users-api')));
    }
}
