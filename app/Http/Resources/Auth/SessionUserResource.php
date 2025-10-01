<?php

namespace App\Http\Resources\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
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
            'id'                => $this->id,
            'name'              => $this->name,
            'email'             => $this->email,
            'email_verified_at' => $this->email_verified_at,
            'created_at'        => $this->created_at,
            'updated_at'        => $this->updated_at,

            'theme' => $this->whenLoaded('appearance') ? [
                'mode'      => $this->appearance->mode ?? 'system',
                'primary'   => $this->appearance->primary_color ?? 'green',
                'secondary' => $this->appearance->secondary_color ?? 'slate',
            ] : [],
        ];
    }
}
