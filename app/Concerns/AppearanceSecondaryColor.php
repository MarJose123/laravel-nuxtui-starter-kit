<?php

namespace App\Concerns;

enum AppearanceSecondaryColor: string
{
    case slate = 'slate';
    case gray = 'gray';
    case zinc = 'zinc';
    case neutral = 'neutral';
    case stone = 'stone';

    public function label(): string
    {
        return match ($this) {
            self::slate   => 'Slate',
            self::gray    => 'Gray',
            self::zinc    => 'Zinc',
            self::neutral => 'Neutral',
            self::stone   => 'Stone',
        };
    }
}
