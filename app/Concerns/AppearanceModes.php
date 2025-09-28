<?php

namespace App\Concerns;

enum AppearanceModes: string
{
    case light = 'light';
    case dark = 'dark';
    case auto = 'auto';

    public function label(): string
    {
        return match ($this) {
            self::light  => 'Light',
            self::dark   => 'Dark',
            self::auto   => 'System',
        };
    }
}
