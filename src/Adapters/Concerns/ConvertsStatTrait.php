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
        if (is_array($value)) {
            $convertedStats = [];
            foreach($value as $element) {
                $convertedStats[] = $this->convertValueToString($element);
            }

            return $convertedStats;
        }

        return $this->convertValueToString($value);
    }

    protected function convertValueToString(mixed $value): string
    {
        return (string) EnumConverter::convertIfEnum($value);
    }
}
