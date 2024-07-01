<?php

namespace Cosmastech\DatadogStatsLaravel\InMemory\Models;

class InMemoryStatsValueObject
{
    public function __construct(
        /** @var array<int, InMemoryTimingValueObject> */
        public array $timing = [],
        /** @var array<int, ArrayCountValueObject> */
        public array $count = [],
        /** @var array<int, InMemoryGaugeValueObject> */
        public array $gauge = [],
        public array $set = [],
        public array $histogram = [],
        public array $timer = [],
        public array $distribution = []
    ) {}
}