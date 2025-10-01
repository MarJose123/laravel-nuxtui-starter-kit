<?php

namespace App\Http\Controllers\Settings;

use App\Actions\User\CreateUserTheme;
use App\Concerns\AppearancePrimaryColor;
use App\Concerns\AppearanceSecondaryColor;
use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\ThemeRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AppearanceController extends Controller
{
    public function __construct(private readonly CreateUserTheme $userTheme) {}

    public function edit(Request $request): Response
    {
        return Inertia::render('settings/Appearance');
    }

    public function store(ThemeRequest $request): RedirectResponse
    {

        /** @var User $user */
        $user = $request->user();

        $this->userTheme->handle($user, [
            'mode'            => $request->mode,
            'primary'         => $request->primary ?? AppearancePrimaryColor::green,
            'secondary'       => $request->secondary ?? AppearanceSecondaryColor::slate,
        ]);

        return to_route('settings.appearance.edit');
    }
}
