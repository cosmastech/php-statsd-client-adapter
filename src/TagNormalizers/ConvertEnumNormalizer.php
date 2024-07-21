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
        $normalizedTags = [];
        foreach ($tags as $key => $value) {
            $normalizedTags[$key] = EnumConverter::convertIfEnum($value);
        }

        return $normalizedTags;
    }
}
