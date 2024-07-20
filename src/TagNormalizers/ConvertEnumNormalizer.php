<?php

namespace Cosmastech\StatsDClientAdapter\TagNormalizers;

use Cosmastech\StatsDClientAdapter\Utility\EnumConverter;

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
        return EnumConverter::convertIfEnum($value);
    }
}
