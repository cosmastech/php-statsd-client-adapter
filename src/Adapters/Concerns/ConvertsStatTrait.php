<?php

namespace Cosmastech\StatsDClientAdapter\Adapters\Concerns;

use Cosmastech\StatsDClientAdapter\Utility\EnumConverter;

trait ConvertsStatTrait
{
    /**
     * @param  mixed  $value
     * @return string|array<int, string>
     * @phpstan-return ($value is array ? array<int, string> : string)
     */
    protected function convertStat(mixed $value): string|array
    {
        if (is_array($value) && array_is_list($value)) {
            return array_map($this->convertStat(...), $value); /* @phpstan-ignore return.type */
        }

        return (string) EnumConverter::convertIfEnum($value);
    }
}
