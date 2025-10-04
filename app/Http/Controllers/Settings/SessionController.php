<?php

namespace App\Http\Controllers\Settings;

use App\Actions\Sessions\DeleteUserSessions;
use App\Actions\Sessions\RetrieveApiUserSession;
use App\Actions\Sessions\RetrieveWebUserSession;
use App\Http\Controllers\Controller;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;
use Laravel\Fortify\Actions\ConfirmPassword;

class SessionController extends Controller
{
    public function __construct(
        public readonly StatefulGuard $guard,
        private readonly DeleteUserSessions $deleteUserSessions,
        private readonly RetrieveWebUserSession $retrieveWebUserSession,
        private readonly RetrieveApiUserSession $retrieveApiUserSession
    ) {}

    public function edit(Request $request): Response
    {
        return Inertia::render('settings/Sessions', [
            'webSessions' => $this->retrieveWebUserSession->handle($request),
            'apiSessions' => $this->retrieveApiUserSession->handle($request),
        ]);
    }

    /**
     * Log out from other browser sessions.
     *
     * @param Request $request
     *
     * @throws AuthenticationException
     *
     * @return RedirectResponse
     */
    public function destroy(Request $request): RedirectResponse
    {
        $confirmed = app(ConfirmPassword::class)(
            $this->guard, $request->user(), $request->input('password')
        );

        if (! $confirmed) {
            throw ValidationException::withMessages([
                'password' => __('The password is incorrect.'),
            ]);
        }

        $this->deleteUserSessions->handle($request, $this->guard);

        return back();

    }
}
