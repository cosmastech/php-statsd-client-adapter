<?php

namespace Cosmastech\StatsDClientAdapter\TagNormalizers;

class NoopTagNormalizer implements TagNormalizer
{
    public function normalize(array $tags): array
    {
        return $tags;
    }
}
