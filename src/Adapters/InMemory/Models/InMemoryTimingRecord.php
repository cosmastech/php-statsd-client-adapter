<?php

namespace Cosmastech\StatsDClientAdapter\Adapters\InMemory\Models;

use DateTimeImmutable;

readonly class InMemoryTimingRecord
{
    /**
     * @param  string  $stat
     * @param  float  $durationMilliseconds
     * @param  float  $sampleRate
     * @param  array<mixed, mixed>  $tags
     * @param  DateTimeImmutable  $recordedAt
     */
    public function __construct(
        public string $stat,
        public float $durationMilliseconds,
        public float $sampleRate,
        public array $tags,
        public DateTimeImmutable $recordedAt,
    ) {
    }
}
