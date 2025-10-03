<?php

namespace App\Http\Requests\Api\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'email'       => 'required|string|email',
            'password'    => 'required|string',
            'device_name' => 'required|string',
            'remember'    => 'boolean|sometimes',
        ];
    }
}
