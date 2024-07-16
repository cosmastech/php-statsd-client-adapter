<?php

namespace Cosmastech\StatsDClientAdapter\Utility;

use BackedEnum;
use UnitEnum;

class EnumHelper
{
    public static function tryConvertEnumToName(mixed $value): mixed
    {
        if (! $value instanceof UnitEnum) {
            return $value;
        }

        if ($value instanceof BackedEnum) {
            return $value->value;
        }

        return $value->name;
    }
}
