<?php

namespace Cosmastech\DatadogStatsLaravel\InMemory;

use Cosmastech\DatadogStatsLaravel\InMemory\Models\ArrayCountValueObject;
use Cosmastech\DatadogStatsLaravel\InMemory\Models\InMemoryGaugeValueObject;
use Cosmastech\DatadogStatsLaravel\InMemory\Models\InMemoryStatsValueObject;
use Cosmastech\DatadogStatsLaravel\InMemory\Models\InMemoryTimingValueObject;
use Cosmastech\DatadogStatsLaravel\StatsDClient;
use Psr\Clock\ClockInterface;

class InMemoryClient implements StatsDClient
{
    private InMemoryStatsValueObject $stats;
    private readonly ClockInterface $clock;

    public function __construct(ClockInterface $clock)
    {
        $this->clock = $clock;

        $this->stats = new InMemoryStatsValueObject();
    }

    public function timing(string $stat, float $time, float $sampleRate = 1.0, array $tags = []): void
    {
        $this->stats->timing[] = new InMemoryTimingValueObject(
            $stat,
            $time,
            $sampleRate,
            $tags,
            $this->clock->now()
        );
    }

    public function microTiming(string $stat, float $time, float $sampleRate = 1.0, array $tags = []): void
    {
        $this->stats->timing[] = new InMemoryTimingValueObject(
            $stat,
            $time * 1000,
            $sampleRate,
            $tags,
            $this->clock->now()
        );
    }

    public function gauge(string $stat, float $value, float $sampleRate = 1.0, array $tags = []): void
    {
        $this->stats->gauge[] = new InMemoryGaugeValueObject(
            $stat,
            $value,
            $sampleRate,
            $tags,
            $this->clock->now()
        );
    }

    public function histogram(string $stat, float $value, float $sampleRate = 1.0, array $tags = []): void
    {
        // TODO: Implement histogram() method.
    }

    public function distribution(string $stat, float $value, float $sampleRate = 1.0, array $tags = []): void
    {
        // TODO: Implement distribution() method.
    }

    public function set(string $stat, float|string $value, float $sampleRate = 1.0, array $tags = []): void
    {
        // TODO: Implement set() method.
    }

    public function increment(array|string $stats, float $sampleRate = 1.0, array $tags = [], int $value = 1): void
    {
        $this->updateStats($stats, $value, $sampleRate, $tags);
    }

    public function decrement(array|string $stats, float $sampleRate = 1.0, array $tags = [], int $value = -1): void
    {
        if ($value < 0) {
            $value *= -1;
        }

        $this->updateStats($stats, $value, $sampleRate, $tags);
    }

    public function updateStats(array|string $stats, int $delta = 1, $sampleRate = 1.0, $tags = null): void
    {
        $stats = (array) $stats;
        $now = $this->clock->now();

        foreach ($stats as $stat) {
            $this->stats->count[] = new ArrayCountValueObject($stat, $delta, $sampleRate, $tags, $now);
        }
    }

    public function getStats(): InMemoryStatsValueObject
    {
        return $this->stats;
    }
}