<?php

namespace App\Actions\User;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CreateUser
{
    /**
     * @throws \Throwable
     */
    public function handle(array $attributes)
    {
        return DB::transaction(function () use ($attributes) {
            $user = User::create([
                ...$attributes,
                'password' => (Hash::make($attributes['password'])),
            ]);

            // you can add more logic here, like adding roles, etc.
            return $user;
        });
    }
}
