<?php

namespace App\Http\Controllers\Auth;

use App\Actions\User\CreateUser;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class RegisterUserController extends Controller
{
    public function __construct(private readonly CreateUser $createUser) {}

    public function create(): Response
    {
        return Inertia::render('auth/Register');
    }

    /**
     * @throws \Throwable
     */
    public function store(RegisterRequest $request)
    {
        $user = $this->createUser->handle($request->validated());

        event(new Registered($user));

        Auth::login($user);

        return to_route('dashboard');

    }
}
