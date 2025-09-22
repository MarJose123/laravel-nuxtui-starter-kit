<?php

namespace App\Http\Controllers\Auth;

use App\Actions\Auth\LogoutUser;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LogoutController extends Controller
{
    public function __construct(private readonly LogoutUser $logoutUser) {}

    public function destroy(Request $request): RedirectResponse
    {
        $this->logoutUser->handle($request);

        return redirect('/');
    }
}
