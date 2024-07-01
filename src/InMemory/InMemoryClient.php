<?php

namespace Cosmastech\StatsDClient\InMemory;

use Cosmastech\StatsDClient\InMemory\Models\InMemoryCountRecord;
use Cosmastech\StatsDClient\InMemory\Models\InMemoryDistributionRecord;
use Cosmastech\StatsDClient\InMemory\Models\InMemoryGaugeRecord;
use Cosmastech\StatsDClient\InMemory\Models\InMemoryHistogramRecord;
use Cosmastech\StatsDClient\InMemory\Models\InMemorySetRecord;
use Cosmastech\StatsDClient\InMemory\Models\InMemoryStatsRecord;
use Cosmastech\StatsDClient\InMemory\Models\InMemoryTimingRecord;
use Cosmastech\StatsDClient\StatsDClient;
use Psr\Clock\ClockInterface;

class InMemoryClient implements StatsDClient
{
    private InMemoryStatsRecord $stats;
    private readonly ClockInterface $clock;

    public function __construct(ClockInterface $clock)
    {
        $this->clock = $clock;

        $this->reset();
    }

    public function timing(string $stat, float $time, float $sampleRate = 1.0, array $tags = []): void
    {
        $this->stats->timing[] = new InMemoryTimingRecord(
            $stat,
            $time,
            $sampleRate,
            $tags,
            $this->clock->now()
        );
    }

    public function gauge(string $stat, float $value, float $sampleRate = 1.0, array $tags = []): void
    {
        $this->stats->gauge[] = new InMemoryGaugeRecord(
            $stat,
            $value,
            $sampleRate,
            $tags,
            $this->clock->now()
        );
    }

    public function histogram(string $stat, float $value, float $sampleRate = 1.0, array $tags = []): void
    {
        $this->stats->histogram[] = new InMemoryHistogramRecord(
            $stat,
            $value,
            $sampleRate,
            $tags,
            $this->clock->now()
        );
    }

    public function distribution(string $stat, float $value, float $sampleRate = 1.0, array $tags = []): void
    {
        $this->stats->distribution[] = new InMemoryDistributionRecord(
            $stat,
            $value,
            $sampleRate,
            $tags,
            $this->clock->now()
        );
    }

    public function set(string $stat, float|string $value, float $sampleRate = 1.0, array $tags = []): void
    {
        $this->stats->distribution[] = new InMemorySetRecord(
            $stat,
            $value,
            $sampleRate,
            $tags,
            $this->clock->now()
        );
    }

    public function increment(array|string $stats, float $sampleRate = 1.0, array $tags = [], int $value = 1): void
    {
        $this->updateStats($stats, $value, $sampleRate, $tags);
    }

    public function decrement(array|string $stats, float $sampleRate = 1.0, array $tags = [], int $value = -1): void
    {
        if ($value > 0) {
            $value *= -1;
        }

        $this->updateStats($stats, $value, $sampleRate, $tags);
    }

    public function updateStats(array|string $stats, int $delta = 1, $sampleRate = 1.0, $tags = null): void
    {
        $stats = (array) $stats;
        $now = $this->clock->now();

        foreach ($stats as $stat) {
            $this->stats->count[] = new InMemoryCountRecord($stat, $delta, $sampleRate, $tags, $now);
        }
    }

    public function getStats(): InMemoryStatsRecord
    {
        return $this->stats;
    }

    public function reset(): void
    {
        $this->stats = new InMemoryStatsRecord();
    }
}
