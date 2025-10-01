<?php

namespace App\Http\Controllers\Settings;

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
    public function edit(Request $request): Response
    {
        return Inertia::render('settings/Appearance');
    }

    public function store(ThemeRequest $request): RedirectResponse
    {

        /** @var User $user */
        $user = $request->user();

        $user->appearance()->updateOrCreate(
            [
                'user_id' => $user->id,
            ],
            [
                'mode'            => $request->mode,
                'primary_color'   => $request->primary ?? AppearancePrimaryColor::green,
                'secondary_color' => $request->secondary ?? AppearanceSecondaryColor::slate,
            ]
        );

        return to_route('settings.appearance.edit');
    }
}
