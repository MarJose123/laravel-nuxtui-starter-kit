<?php

namespace App\Http\Requests\Settings;

use App\Concerns\AppearanceModes;
use App\Concerns\AppearancePrimaryColor;
use App\Concerns\AppearanceSecondaryColor;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ThemeRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'mode' => [
                'required',
                Rule::enum(AppearanceModes::class),
            ],
            'primary' => [
                'sometimes',
                Rule::enum(AppearancePrimaryColor::class),
            ],
            'secondary' => [
                'sometimes',
                Rule::enum(AppearanceSecondaryColor::class),
            ],
        ];
    }
}
