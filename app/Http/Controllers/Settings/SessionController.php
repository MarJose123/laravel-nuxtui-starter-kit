<?php

namespace App\Http\Controllers\Settings;

use App\Actions\Auth\RevokeApiUserToken;
use App\Actions\Sessions\DeleteUserSessions;
use App\Actions\Sessions\RetrieveApiUserSession;
use App\Actions\Sessions\RetrieveWebUserSession;
use App\Http\Controllers\Controller;
use App\Services\InertiaNotification;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;
use Laravel\Fortify\Actions\ConfirmPassword;
use Laravel\Fortify\Features;

class SessionController extends Controller implements HasMiddleware
{
    public function __construct(
        public readonly StatefulGuard $guard,
        private readonly DeleteUserSessions $deleteUserSessions,
        private readonly RetrieveWebUserSession $retrieveWebUserSession,
        private readonly RetrieveApiUserSession $retrieveApiUserSession,
        private readonly RevokeApiUserToken $revokeApiUserToken,
    ) {}

    public static function middleware()
    {
        return Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword')
            ? [new Middleware('password.confirm', only: ['edit'])]
            : [];
    }

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
     * @throws Exception
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

        $request->boolean('api') ?
            $this->revokeApiUserToken->handle($request) :
            $this->deleteUserSessions->handle($request, $this->guard);

        InertiaNotification::make()
            ->success()
            ->title('Sessions revoked')
            ->message('The sessions have been revoked.')
            ->send();

        return back();

    }
}
