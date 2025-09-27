<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\InertiaNotification;
use Exception;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class UserEmailVerificationController extends Controller
{
    public function notice(Request $request): RedirectResponse|Response
    {
        return $request->user()->hasVerifiedEmail()
            ? redirect()->intended(route('dashboard', absolute: false))
            : Inertia::render('auth/VerifyEmailPrompt');
    }

    public function verify(EmailVerificationRequest $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard', absolute: false).'?verified=1');
        }

        $request->fulfill();

        return redirect()->intended(route('dashboard', absolute: false).'?verified=1');
    }

    /**
     * @throws Exception
     */
    public function store(Request $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard', absolute: false));
        }

        InertiaNotification::make()
            ->success()
            ->message('A new verification link has been sent to your email address.')
            ->send();

        $request->user()->sendEmailVerificationNotification();

        return back();
    }
}
