<?php

namespace App\Http\Controllers\Auth;

use App\Actions\Auth\LoginUser;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

class LoginController extends Controller
{
    public function __construct(private readonly LoginUser $loginUser) {}

    /**
     * Show the application's login form.
     */
    public function create(Request $request): Response
    {
        return Inertia::render('auth/Login', [
            'canResetPassword'   => Route::has('password.request'),
            'canRegister'        => Route::has('register'),
            'status'             => $request->session()->get('notification'),
        ]);
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $user = $this->loginUser->handle($request);

        Auth::login($user, $request->boolean('remember'));

        $request->session()->regenerate();

        return redirect()->intended(route('dashboard', absolute: false));

    }
}
