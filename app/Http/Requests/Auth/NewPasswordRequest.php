<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class NewPasswordRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'token'    => ['required'],
            'email'    => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'confirmed',
                Password::min(8)
                    ->letters()
                    ->numbers()
                    ->mixedCase() // lower-case and upper-case letter
                    ->symbols()
                    ->uncompromised(),
            ],
            'password_confirmation' => ['required'],
        ];
    }
}
