<?php

namespace App\Concerns;

enum AppearancePrimaryColor: string
{
    case red = 'red';
    case orange = 'orange';
    case amber = 'amber';
    case yellow = 'yellow';
    case lime = 'lime';
    case green = 'green';
    case emerald = 'emerald';
    case teal = 'teal';
    case cyan = 'cyan';
    case sky = 'sky';
    case blue = 'blue';
    case indigo = 'indigo';
    case violet = 'violet';
    case purple = 'purple';
    case fuchsia = 'fuchsia';
    case pink = 'pink';
    case rose = 'rose';

    public function label(): string
    {
        return match ($this) {
            self::red     => 'Red',
            self::orange  => 'Orange',
            self::amber   => 'Amber',
            self::yellow  => 'Yellow',
            self::lime    => 'Lime',
            self::green   => 'Green',
            self::emerald => 'Emerald',
            self::teal    => 'Teal',
            self::cyan    => 'Cyan',
            self::sky     => 'Sky',
            self::blue    => 'Blue',
            self::indigo  => 'Indigo',
            self::violet  => 'Violet',
            self::purple  => 'Purple',
            self::fuchsia => 'Fuchsia',
            self::pink    => 'Pink',
            self::rose    => 'Rose',
        };
    }
}
