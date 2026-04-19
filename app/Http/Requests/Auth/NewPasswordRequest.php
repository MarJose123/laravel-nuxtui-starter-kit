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
                Password::default(),
            ],
            'password_confirmation' => ['required'],
        ];
    }
}
