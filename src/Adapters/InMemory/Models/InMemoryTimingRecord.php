<?php

namespace Cosmastech\StatsDClientAdapter\Adapters\InMemory\Models;

use DateTimeImmutable;

readonly class InMemoryTimingRecord
{
    public function __construct(
        public string $stat,
        public float $durationMilliseconds,
        public float $sampleRate,
        public array $tags,
        public DateTimeImmutable $recordedAt,
    ) {
    }
}