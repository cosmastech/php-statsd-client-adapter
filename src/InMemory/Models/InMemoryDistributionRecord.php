<?php

namespace Cosmastech\StatsDClient\InMemory\Models;

use DateTimeImmutable;

readonly class InMemoryDistributionRecord
{
    public function __construct(
        public string $stat,
        public float $value,
        public float $sampleRate,
        public array $tags,
        public DateTimeImmutable $recordedAt,
    ) {}
}