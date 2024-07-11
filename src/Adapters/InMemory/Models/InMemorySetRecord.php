<?php

namespace Cosmastech\StatsDClientAdapter\Adapters\InMemory\Models;

use Cosmastech\StatsDClientAdapter\Adapters\InMemory\Models\Contracts\RecordInterface;
use DateTimeImmutable;

readonly class InMemorySetRecord implements RecordInterface
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
