<?php

namespace Cosmastech\StatsDClientAdapter\Normalizers;

class NoopNormalizer implements Normalizer
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
