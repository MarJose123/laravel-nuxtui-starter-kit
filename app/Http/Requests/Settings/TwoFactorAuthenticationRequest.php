<?php

namespace App\Http\Requests\Settings;

use Illuminate\Foundation\Http\FormRequest;
use Laravel\Fortify\Features;
use Laravel\Fortify\InteractsWithTwoFactorState;

class TwoFactorAuthenticationRequest extends FormRequest
{
    use InteractsWithTwoFactorState;

    public function rules(): array
    {
        return [

        ];
    }

    public function authorize(): bool
    {
        return Features::enabled(Features::twoFactorAuthentication());
    }
}
