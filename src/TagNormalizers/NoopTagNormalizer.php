<?php

namespace Cosmastech\StatsDClientAdapter\TagNormalizers;

class NoopTagNormalizer implements TagNormalizer
{
    /**
     * @param  array<mixed, mixed>  $tags
     * @return array<mixed, mixed>
     */
    public function normalize(array $tags): array
    {
        return $tags;
    }
}
