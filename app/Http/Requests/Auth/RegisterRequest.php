<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name'     => ['required', 'string', 'max:255', 'min:3'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users'],
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
