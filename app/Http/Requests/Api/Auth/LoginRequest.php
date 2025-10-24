<?php

namespace App\Http\Requests\Api\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            /**
             * The email address of the user.
             */
            'email'       => ['required', 'string', 'email'],
            /**
             * The password of the user.
             */
            'password'    => ['required', 'string'],
            /**
             * The name of the device that the user is using to login.
             */
            'device_name' => ['required', 'string'],
            /**
             * If the user wants to be remembered and the token will be extended up to `30days` instead of `1day`.
             *
             * @example true
             */
            'remember'    => ['boolean', 'sometimes'],
        ];
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate-limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return $this->string('email')
            ->lower()
            ->append('|api')
            ->append('|'.$this->ip())
            ->transliterate()
            ->value();
    }
}
