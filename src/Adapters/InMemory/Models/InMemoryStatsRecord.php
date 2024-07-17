<?php

namespace Cosmastech\StatsDClientAdapter\Adapters\InMemory\Models;

use Cosmastech\StatsDClientAdapter\Adapters\InMemory\Models\Concerns\GetAndSetRecordsTrait;
use Cosmastech\StatsDClientAdapter\Adapters\InMemory\Models\Contracts\InMemoryStatsRecordInterface;
use Cosmastech\StatsDClientAdapter\Adapters\InMemory\Models\Contracts\RecordInterface;

/**
 * Container class for storing all stats.
 */
class InMemoryStatsRecord implements RecordInterface, InMemoryStatsRecordInterface
{
    use GetAndSetRecordsTrait;

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
    protected array $distribution;

    public function __construct()
    {
        $this->resetStats();
    }

    public function flush(): void
    {
        $this->resetStats();
    }

    /**
     * Empty all stat array containers.
     *
     * @return void
     */
    protected function resetStats(): void
    {
        $this->timing = [];
        $this->count = [];
        $this->gauge = [];
        $this->set = [];
        $this->histogram = [];
        $this->distribution = [];
    }
}
