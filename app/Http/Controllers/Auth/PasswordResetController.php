<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class PasswordResetController extends Controller
{
    /**
     * Show the password-reset link request page.
     */
    public function create(): Response
    {
        return Inertia::render('auth/ForgotPassword');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws ValidationException
     * @throws Exception
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        Password::sendResetLink(
            $request->only('email')
        );

        return back();
    }
}
