<?php

namespace App\Models;

use App\Concerns\AppearanceModes;
use App\Concerns\AppearancePrimaryColor;
use App\Concerns\AppearanceSecondaryColor;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Appearance extends Model
{
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'mode',
        'primary_color',
        'secondary_color',
    ];

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected function casts(): array
    {
        return [
            'mode'            => AppearanceModes::class,
            'primary_color'   => AppearancePrimaryColor::class,
            'secondary_color' => AppearanceSecondaryColor::class,
        ];
    }
}
