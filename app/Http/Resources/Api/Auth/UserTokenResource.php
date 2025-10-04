<?php

namespace App\Http\Resources\Api\Auth;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Override;

class UserTokenResource extends JsonResource
{
    public static $wrap;

    /**
     * @param Request $request
     *
     * @return array
     */
    #[Override]
    public function toArray(Request $request): array
    {
        return [
            /**
             * Access token to be used for authenticated requests.
             *
             * @var string $token
             *
             * @example 2|DT5CFxoDwc8e04IdodtNdLY1Sp65op5yFunbEbrN82f7b5b8
             */
            'access_token' => $this->token,
            /**
             * Refresh token is used to get a new access token when it expires.
             *
             * @var string $refresh
             *
             * @example 6hTB2tLiYvVnpkJhVZjG8DZW7tm2GYIX
             */
            'refresh'    => $this->refresh,
            /**
             * Expiration time of the token in seconds.
             *
             * @var int $expires_in
             *
             * @example 3600 or 86400
             */
            'expires_in' => $this->expires_in,
        ];
    }
}
