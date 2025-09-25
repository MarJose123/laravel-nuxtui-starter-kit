<?php

namespace App\Actions\User;

use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Throwable;

class CreateUser
{
    /**
     * @throws Throwable
     */
    public function handle(array $attributes): User
    {
        return DB::transaction(function () use ($attributes) {
            $user = User::create([
                ...$attributes,
                'password' => (Hash::make($attributes['password'])),
            ]);

            if ($user instanceof MustVerifyEmail) {
                $user->sendEmailVerificationNotification();
            }

            // you can add more logic here, like adding roles, etc.

            return $user;
        });
    }
}
