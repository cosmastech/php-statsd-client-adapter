<?php

namespace Cosmastech\StatsDClient\TagNormalizers;

use BackedEnum;
use UnitEnum;

class ConvertEnumNormalizer implements TagNormalizer
{
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
        if (!$value instanceof UnitEnum) {
            return $value;
        }

        if ($value instanceof BackedEnum) {
            return $value->value;
        }

        return $value->name;
    }
}
