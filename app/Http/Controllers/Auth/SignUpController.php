<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\SignUpRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SignUpController extends Controller
{
    public function create(Request $request)
    {
        return Inertia::render('auth/SignUp');
    }

    public function store(SignUpRequest $request) {}
}
