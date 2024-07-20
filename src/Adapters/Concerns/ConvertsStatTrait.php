<?php

namespace Cosmastech\StatsDClientAdapter\Adapters\Concerns;

use Cosmastech\StatsDClientAdapter\Utility\EnumConverter;

trait ConvertsStatTrait
{
    protected function convertStat(mixed $value): mixed
    {
        if (is_array($value) && array_is_list($value)) {
            return array_map($this->convertStat(...), $value);
        }

        return EnumConverter::convertIfEnum($value);
    }
}
