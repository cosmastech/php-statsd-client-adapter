<?php

namespace Cosmastech\StatsDClient\Tests\Doubles;

use Cosmastech\StatsDClient\TagNormalizers\TagNormalizer;

class TagNormalizerSpy implements TagNormalizer
{
    private array $normalizeCalls = [];

    public function normalize(array $tags): array
    {
        $this->normalizeCalls[] = $tags;

        return $tags;
    }

    public function getNormalizeCalls(): array
    {
        return $this->normalizeCalls;
    }
}
