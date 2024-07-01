<?php

namespace Cosmastech\DatadogStatsLaravel\InMemory\Models;

use DateTimeImmutable;

readonly class InMemoryHistogramRecord
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