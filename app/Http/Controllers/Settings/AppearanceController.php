<?php

namespace App\Http\Controllers\Settings;

use App\Concerns\AppearanceModes;
use App\Concerns\AppearancePrimaryColor;
use App\Concerns\AppearanceSecondaryColor;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class AppearanceController extends Controller
{
    public function edit(Request $request): Response
    {
        return Inertia::render('settings/Appearance');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'appearance' => [
                'required',
                Rule::enum(AppearanceModes::class),
            ],
            'primary_color' => [
                'sometimes',
                Rule::enum(AppearancePrimaryColor::class),
            ],
            'secondary_color' => [
                'sometimes',
                Rule::enum(AppearanceSecondaryColor::class),
            ],
        ]);

        /** @var User $user */
        $user = $request->user();

        $user->appearance->updateOrCreate(
            [
                'user_id' => $user->id,
            ],
            [
                'mode'            => $request->appearance,
                'primary_color'   => $request->primary_color ?? AppearancePrimaryColor::green,
                'secondary_color' => $request->secondary_color ?? AppearanceSecondaryColor::slate,
            ]
        );

        return to_route('settings.appearance.edit');
    }
}
