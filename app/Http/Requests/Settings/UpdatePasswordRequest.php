<?php

namespace App\Http\Requests\Settings;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UpdatePasswordRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'password' => ['required', 'confirmed',
                Password::min(8)
                    ->letters()
                    ->numbers()
                    ->mixedCase() // lower-case and upper-case letter
                    ->symbols()
                    ->uncompromised(),
            ],
            'password_confirmation' => ['required'],
            'current_password'      => ['required', 'current_password'],
        ];
    }
}
