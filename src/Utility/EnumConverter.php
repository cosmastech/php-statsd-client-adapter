<?php

namespace Cosmastech\StatsDClientAdapter\Utility;

use BackedEnum;
use UnitEnum;

class EnumConverter
{
    public static function convert(UnitEnum $value): string|int
    {
        if ($value instanceof BackedEnum) {
            return $value->value;
        }

        return $value->name;
    }

    public static function convertIfEnum(mixed $value): mixed
    {
        return $value instanceof UnitEnum ? static::convert($value) : $value;
    }
}
