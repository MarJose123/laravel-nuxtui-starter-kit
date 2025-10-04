<?php

namespace App\Http\Requests\Api\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RefreshTokenRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            /**
             * The refresh token to be used for refreshing the access token.
             *
             * @var string $refresh
             *
             * @example 6hTB2tLiYvVnpkJhVZjG8DZW7tm2GYIX
             */
            'refresh_token' => ['required', 'string'],
            /**
             * If the user wants to be remembered and the token will be extended up to `30days` instead of `1day`.
             */
            'remember'      => ['boolean', 'sometimes'],
        ];
    }
}
