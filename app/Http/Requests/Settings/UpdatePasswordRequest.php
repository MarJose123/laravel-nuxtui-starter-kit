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
                Password::default(),
            ],
            'password_confirmation' => ['required'],
            'current_password'      => ['required', 'current_password'],
        ];
    }
}
