<?php

namespace App\Traits;

trait EnumTrait
{
    public static function options(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function toString(): string
    {
        return implode(',', self::options());
    }
}
