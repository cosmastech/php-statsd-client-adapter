<?php

namespace Cosmastech\StatsDClientAdapter\Adapters\InMemory\Models\Contracts;

use Cosmastech\StatsDClientAdapter\Adapters\InMemory\Models\InMemoryCountRecord;
use Cosmastech\StatsDClientAdapter\Adapters\InMemory\Models\InMemoryDistributionRecord;
use Cosmastech\StatsDClientAdapter\Adapters\InMemory\Models\InMemoryGaugeRecord;
use Cosmastech\StatsDClientAdapter\Adapters\InMemory\Models\InMemoryHistogramRecord;
use Cosmastech\StatsDClientAdapter\Adapters\InMemory\Models\InMemorySetRecord;
use Cosmastech\StatsDClientAdapter\Adapters\InMemory\Models\InMemoryTimingRecord;

interface InMemoryStatsRecordInterface
{
    public function recordTiming(InMemoryTimingRecord $inMemoryTimingRecord): void;

    /**
     * @return array<int, InMemoryTimingRecord>|iterable<InMemoryTimingRecord>
     */
    public function getTimings();

    public function recordCount(InMemoryCountRecord $inMemoryCountRecord): void;

    /**
     * @return array<int, InMemoryCountRecord>|iterable<InMemoryCountRecord>
     */
    public function getCounts();

    public function recordGauge(InMemoryGaugeRecord $inMemoryGaugeRecord): void;

    /**
     * @return array<int, InMemoryGaugeRecord>|iterable<InMemoryGaugeRecord>
     */
    public function getGauges();


    public function recordSet(InMemorySetRecord $inMemorySetRecord): void;

    /**
     * @return array<int, InMemorySetRecord>|iterable<InMemorySetRecord>
     */
    public function getSets();

    public function recordHistogram(InMemoryHistogramRecord $inMemoryHistogramRecord): void;

    /**
     * @return array<int, InMemoryHistogramRecord>|iterable<InMemoryHistogramRecord>
     */
    public function getHistograms();

    public function recordDistribution(InMemoryDistributionRecord $inMemoryDistributionRecord): void;

    /**
     * @return array<int, InMemoryDistributionRecord>|iterable<InMemoryDistributionRecord>
     */
    public function getDistributions();

    public function flush(): void;
}
