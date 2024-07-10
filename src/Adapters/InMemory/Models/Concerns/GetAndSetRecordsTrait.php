<?php

namespace Cosmastech\StatsDClientAdapter\Adapters\InMemory\Models\Concerns;

use Cosmastech\StatsDClientAdapter\Adapters\InMemory\Models\InMemoryCountRecord;
use Cosmastech\StatsDClientAdapter\Adapters\InMemory\Models\InMemoryDistributionRecord;
use Cosmastech\StatsDClientAdapter\Adapters\InMemory\Models\InMemoryGaugeRecord;
use Cosmastech\StatsDClientAdapter\Adapters\InMemory\Models\InMemoryHistogramRecord;
use Cosmastech\StatsDClientAdapter\Adapters\InMemory\Models\InMemorySetRecord;
use Cosmastech\StatsDClientAdapter\Adapters\InMemory\Models\InMemoryTimingRecord;

trait GetAndSetRecordsTrait
{
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

    public function recordDistribution(InMemoryDistributionRecord $inMemoryDistributionRecord): void
    {
        $this->distribution[] = $inMemoryDistributionRecord;
    }

    /**
     * @return array<int, InMemoryDistributionRecord>
     */
    public function getDistributions(): array
    {
        return $this->distribution;
    }
}
