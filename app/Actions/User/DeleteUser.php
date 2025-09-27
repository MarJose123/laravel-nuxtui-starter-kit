<?php

namespace App\Actions\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DeleteUser
{
    public function handle(Request $request): void
    {
        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }
}
