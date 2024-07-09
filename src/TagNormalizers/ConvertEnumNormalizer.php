<?php

namespace Cosmastech\StatsDClientAdapter\TagNormalizers;

use BackedEnum;
use UnitEnum;

class ConvertEnumNormalizer implements TagNormalizer
{
    /**
     * @param  array<mixed, mixed>  $tags
     * @return array<mixed, mixed>
     */
    public function normalize(array $tags): array
    {
        $toReturn = [];
        foreach ($tags as $key => $value) {
            $toReturn[$key] = $this->convertEnumToName($value);
        }

        return $toReturn;
    }

    protected function convertEnumToName(mixed $value): mixed
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
