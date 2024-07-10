<?php

namespace Cosmastech\StatsDClientAdapter\Adapters\InMemory\Models;

/**
 * Container class for storing all stats.
 */
class InMemoryStatsRecord
{
    /** @var array<int, InMemoryTimingRecord> */
    protected array $timing;

    /** @var array<int, InMemoryCountRecord> */
    protected array $count;

    /** @var array<int, InMemoryGaugeRecord> */
    protected array $gauge;

    /** @var array<int, InMemorySetRecord> */
    protected array $set;

    /** @var array<int, InMemoryHistogramRecord> */
    protected array $histogram;

    /** @var array<int, InMemoryDistributionRecord> */
    public array $distribution;

    public function __construct()
    {
        $this->flush();
    }

    public function recordTiming(InMemoryTimingRecord $inMemoryTimingRecord): void
    {
        $this->timing[] = $inMemoryTimingRecord;
    }

    /**
     * @return array<int, InMemoryTimingRecord>
     */
    public function getTimings(): array
    {
        return $this->timing;
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

    public function recordGauge(InMemoryGaugeRecord $inMemoryGaugeRecord): void
    {
        $this->gauge[] = $inMemoryGaugeRecord;
    }

    /**
     * @return array<int, InMemoryGaugeRecord>
     */
    public function getGauges(): array
    {
        return $this->gauge;
    }

    public function recordSet(InMemorySetRecord $inMemorySetRecord): void
    {
        $this->set[] = $inMemorySetRecord;
    }

    /**
     * @return array<int, InMemorySetRecord>
     */
    public function getSets(): array
    {
        return $this->set;
    }

    public function recordHistogram(InMemoryHistogramRecord $inMemoryHistogramRecord): void
    {
        $this->histogram[] = $inMemoryHistogramRecord;
    }

    /**
     * @return array<int, InMemoryHistogramRecord>
     */
    public function getHistograms(): array
    {
        return $this->histogram;
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
