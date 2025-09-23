<?php

namespace App\Http\Controllers\Auth;

use App\Actions\User\CreateUser;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Services\InertiaNotification;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RegisterController extends Controller
{
    public function __construct(private readonly CreateUser $createUser)
    {
    }

    public function create(Request $request)
    {
        return Inertia::render('auth/Register');
    }

    /**
     * @throws \Throwable
     */
    public function store(RegisterRequest $request)
    {
        $this->createUser->handle($request->validated());

        InertiaNotification::make()
            ->success()
            ->message('User created successfully.')
            ->title('Success')
            ->send();

        return to_route('login');

    }
}
