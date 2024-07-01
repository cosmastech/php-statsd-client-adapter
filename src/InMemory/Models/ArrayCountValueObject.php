<?php

namespace Cosmastech\DatadogStatsLaravel\InMemory\Models;

use DateTimeImmutable;

class ArrayCountValueObject
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