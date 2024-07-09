<?php

namespace Cosmastech\StatsDClientAdapter\Adapters\InMemory\Models;

use DateTimeImmutable;

readonly class InMemorySetRecord
{
    /**
     * @param  string  $stat
     * @param  float|string  $value
     * @param  float  $sampleRate
     * @param  array<mixed, mixed>  $tags
     * @param  DateTimeImmutable  $recordedAt
     */
    public function __construct(
        public string $stat,
        public float|string $value,
        public float $sampleRate,
        public array $tags,
        public DateTimeImmutable $recordedAt,
    ) {
    }
}
