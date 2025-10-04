<?php

namespace App\Http\Resources\Api\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;
use Override;

/** @mixin User */
class SessionUserResource extends JsonResource
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
             * Id of the user.
             */
            'id'                => $this->id,
            /**
             * Name of the user.
             *
             * @var string $name
             *
             * @example John Doe
             */
            'name'              => $this->name,
            /**
             * Email of the user.
             *
             * @var string $email
             *
             * @example john.doe@mail.com
             */
            'email'             => $this->email,
            /**
             * Email verification date of the user.
             *
             * @var Carbon|null $email_verified_at
             *
             * @example 2024-01-01 00:00:00
             */
            'email_verified_at' => $this->email_verified_at,
            /**
             * Date of the user creation.
             *
             * @var Carbon $created_at
             *
             * @example 2024-01-01 00:00:00
             */
            'created_at'        => $this->created_at,
            /**
             * Date of the user last update.
             *
             * @var Carbon $updated_at
             *
             * @example 2024-01-01 00:00:00
             */
            'updated_at'        => $this->updated_at,
        ];
    }
}
