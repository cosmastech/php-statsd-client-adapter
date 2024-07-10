<?php

namespace Cosmastech\StatsDClientAdapter\Adapters\InMemory\Models;

/**
 * Container class for storing all stats.
 */
class InMemoryStatsRecord
{
    /** @var array<int, InMemoryTimingRecord> */
    public array $timing;

    /** @var array<int, InMemoryCountRecord> */
    public array $count;

    /** @var array<int, InMemoryGaugeRecord> */
    public array $gauge;

    /** @var array<int, InMemorySetRecord> */
    public array $set;

    /** @var array<int, InMemoryHistogramRecord> */
    public array $histogram;

    /** @var array<int, InMemoryDistributionRecord> */
    public array $distribution;

    public function __construct()
    {
        $this->flush();
    }

    public function recordCount(InMemoryCountRecord $inMemoryCountRecord): void
    {
        $this->count[] = $inMemoryCountRecord;
    }

    /**
     * @return array<int, InMemoryCountRecord>
     */
    public function getCounts(): array
    {
        return $this->count;
    }

    public function flush(): void
    {
        $this->timing = [];
        $this->count = [];
        $this->gauge = [];
        $this->set = [];
        $this->histogram = [];
        $this->distribution = [];
    }
}
