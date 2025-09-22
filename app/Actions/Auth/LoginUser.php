<?php

namespace App\Actions\Auth;

use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;

class LoginUser
{
    public function handle(LoginRequest $request): User
    {
        return $request->validateCredentials();
    }
}
