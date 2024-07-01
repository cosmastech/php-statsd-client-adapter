<?php

namespace Cosmastech\StatsDClient\Clients\InMemory\Models;

use DateTimeImmutable;

class InMemoryCountRecord
{
    public function __construct(
        public string $stat,
        public int $count,
        public float $sampleRate,
        public array $tags,
        public DateTimeImmutable $recordedAt,
    ) {
    }
}