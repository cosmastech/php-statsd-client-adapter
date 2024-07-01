<?php

namespace Cosmastech\DatadogStatsLaravel\InMemory\Models;

use DateTimeImmutable;

readonly class InMemoryTimingValueObject
{
    public function __construct(
        public string $stat,
        public float $milliseconds,
        public float $sampleRate,
        public array $tags,
        public DateTimeImmutable $recordedAt,
    ) {}
}