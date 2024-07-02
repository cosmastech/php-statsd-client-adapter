<?php

namespace Cosmastech\StatsDClient\Clients\InMemory\Models;

use DateTimeImmutable;

readonly class InMemorySetRecord
{
    public function __construct(
        public string $stat,
        public float $value,
        public float $sampleRate,
        public array $tags,
        public DateTimeImmutable $recordedAt,
    ) {
    }
}
