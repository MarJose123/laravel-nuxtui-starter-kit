<?php

namespace App\Actions\User;

use App\Models\User;
use Illuminate\Support\Facades\DB;

class CreateUser
{
    public function handle(array $attributes)
    {
        return DB::transaction(function () use ($attributes) {
            $user = User::create($attributes);

            // you can add more logic here, like adding roles, etc.
            return $user;
        });
    }
}
