<?php

namespace Cosmastech\StatsDClient\TagNormalizers;

class NoopTagNormalizer implements TagNormalizer
{
    public function normalize(array $tags): array
    {
        return $tags;
    }
}
