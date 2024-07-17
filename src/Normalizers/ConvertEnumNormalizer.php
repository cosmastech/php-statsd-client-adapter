<?php

namespace Cosmastech\StatsDClientAdapter\Normalizers;

use Cosmastech\StatsDClientAdapter\Utility\EnumHelper;

class ConvertEnumNormalizer implements Normalizer
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
        return EnumHelper::tryConvertEnumToName($value);
    }
}
