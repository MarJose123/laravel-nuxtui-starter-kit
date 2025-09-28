<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AppearanceController extends Controller
{
    public function edit(Request $request): Response
    {
        $appearance = $request->user()->appearances->mode ?? 'auto';

        return Inertia::render('settings/Appearance', [
            'appearance' => $appearance,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'mode' => ['required', 'in:light,dark,auto'],
        ]);

        $request->user()->appearances()->updateOrCreate(
            ['user_id' => $request->user()->id],
            ['mode' => $validated['mode']]
        );

        return to_route('settings.appearance.edit');
    }
}
