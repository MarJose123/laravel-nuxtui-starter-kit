<?php

namespace App\Http\Controllers\Settings;

use App\Actions\User\DeleteUser;
use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\AccountUpdateRequest;
use App\Services\InertiaNotification;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AccountController extends Controller
{
    public function __construct(private readonly DeleteUser $deleteUser) {}

    /**
     * Show the user's profile settings page.
     */
    public function edit(Request $request): Response
    {
        return Inertia::render('settings/Account', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(AccountUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        InertiaNotification::make()
            ->success()
            ->title('Account updated')
            ->message('Your account has been updated')
            ->send();

        return to_route('settings.account.edit');
    }

    /**
     * Delete the user's profile.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
        ]);

        $this->deleteUser->handle($request);

        return redirect('/');
    }
}
