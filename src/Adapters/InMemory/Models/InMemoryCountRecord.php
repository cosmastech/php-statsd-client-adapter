<?php

namespace Cosmastech\StatsDClientAdapter\Adapters\InMemory\Models;

use DateTimeImmutable;

class InMemoryCountRecord
{
    /**
     * @param  string  $stat
     * @param  int  $count
     * @param  float  $sampleRate
     * @param  array<mixed, mixed>  $tags
     * @param  DateTimeImmutable  $recordedAt
     */
    public function __construct(
        public string $stat,
        public int $count,
        public float $sampleRate,
        public array $tags,
        public DateTimeImmutable $recordedAt,
    ) {
    }
}
