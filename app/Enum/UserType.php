<?php

namespace App\Enum;

enum UserType: int
{
    case FRIEND = 0;
    case FAMILY = 1;
    case COLLEAGUE = 2;

    public static function toArray(): array
    {
        return [
            self::FRIEND,
            self::FAMILY,
            self::COLLEAGUE,
        ];
    }
}
