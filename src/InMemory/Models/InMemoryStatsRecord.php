<?php

namespace Cosmastech\DatadogStatsLaravel\InMemory\Models;

class InMemoryStatsRecord
{
    public function __construct(
        /** @var array<int, InMemoryTimingRecord> */
        public array $timing = [],
        /** @var array<int, InMemoryCountRecord> */
        public array $count = [],
        /** @var array<int, InMemoryGaugeRecord> */
        public array $gauge = [],
        /** @var array<int, InMemorySetRecord> */
        public array $set = [],
        /** @var array<int, InMemoryHistogramRecord> */
        public array $histogram = [],
        /** @var array<int, InMemoryDistributionRecord> */
        public array $distribution = []
    ) {
    }
}