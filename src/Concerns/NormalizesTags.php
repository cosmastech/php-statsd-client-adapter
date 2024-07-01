<?php

namespace Cosmastech\StatsDClient\Concerns;

trait NormalizesTags
{
    protected function normalizeTags(array $tags): array
    {
        $toReturn = [];
        foreach($tags as $key => $value) {
            $toReturn[$this->convertEnumToName($key)] = $this->convertEnumToName($value);
        }

        return $toReturn;
    }

    private function convertEnumToName(mixed $value): mixed {
        if (! $value instanceof \UnitEnum) {
            return $value;
        }

        if ($value instanceof \BackedEnum) {
            return $value->value;
        }

        return $value->name;
    }
}