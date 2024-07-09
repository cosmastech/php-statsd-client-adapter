<?php

namespace Cosmastech\StatsDClientAdapter\Tests\Doubles;

use Cosmastech\StatsDClientAdapter\TagNormalizers\TagNormalizer;

class TagNormalizerSpy implements TagNormalizer
{
    /** @var array<mixed, mixed> */
    private array $normalizeCalls = [];

    /**
     * @param  array<mixed, mixed>  $tags
     * @return array<mixed, mixed>
     */
    public function normalize(array $tags): array
    {
        $this->normalizeCalls[] = $tags;

        return $tags;
    }

    /**
     * @return array<mixed, mixed>
     */
    public function getNormalizeCalls(): array
    {
        return $this->normalizeCalls;
    }
}
