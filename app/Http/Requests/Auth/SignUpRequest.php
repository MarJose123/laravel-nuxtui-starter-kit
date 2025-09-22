<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class SignUpRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'string', 'confirmed',
                Password::default()->letters()->numbers()
                    ->symbols()->max(8)->mixedCase(),
            ],
            'password_confirmation' => 'required|string|',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
