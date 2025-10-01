<?php

namespace App\Actions\User;

use App\Models\Appearance;
use App\Models\User;

class CreateUserTheme
{
    public function handle(User $user, array $attribute): Appearance
    {

        return $user->appearance()->updateOrCreate(
            [
                'user_id' => $user->id,
            ],
            [
                'mode'            => $attribute['mode'],
                'primary_color'   => $attribute['primary'],
                'secondary_color' => $attribute['secondary'],
            ]
        );

    }
}
