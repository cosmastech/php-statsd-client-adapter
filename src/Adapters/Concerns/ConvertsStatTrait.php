<?php

namespace Cosmastech\StatsDClientAdapter\Adapters\Concerns;

use Cosmastech\StatsDClientAdapter\Utility\EnumConverter;

trait ConvertsStatTrait
{
    protected function convertStat(mixed $value): mixed
    {
        return EnumConverter::convertIfEnum($value);
    }
}
