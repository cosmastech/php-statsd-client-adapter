<?php

namespace Cosmastech\StatsDClientAdapter\Adapters\InMemory;

use Cosmastech\StatsDClientAdapter\Adapters\Concerns\HasDefaultTagsTrait;
use Cosmastech\StatsDClientAdapter\Adapters\Concerns\TagNormalizerAwareTrait;
use Cosmastech\StatsDClientAdapter\Adapters\Contracts\TagNormalizerAware;
use Cosmastech\StatsDClientAdapter\Adapters\InMemory\Models\InMemoryCountRecord;
use Cosmastech\StatsDClientAdapter\Adapters\InMemory\Models\InMemoryDistributionRecord;
use Cosmastech\StatsDClientAdapter\Adapters\InMemory\Models\InMemoryGaugeRecord;
use Cosmastech\StatsDClientAdapter\Adapters\InMemory\Models\InMemoryHistogramRecord;
use Cosmastech\StatsDClientAdapter\Adapters\InMemory\Models\InMemorySetRecord;
use Cosmastech\StatsDClientAdapter\Adapters\InMemory\Models\InMemoryStatsRecord;
use Cosmastech\StatsDClientAdapter\Adapters\InMemory\Models\InMemoryTimingRecord;
use Cosmastech\StatsDClientAdapter\Adapters\StatsDClientAdapter;
use Cosmastech\StatsDClientAdapter\TagNormalizers\NoopTagNormalizer;
use Cosmastech\StatsDClientAdapter\Utility\Clock;
use Psr\Clock\ClockInterface;

class InMemoryClientAdapter implements StatsDClientAdapter, TagNormalizerAware
{
    use HasDefaultTagsTrait;
    use TagNormalizerAwareTrait;

    protected InMemoryStatsRecord $stats;
    protected readonly ClockInterface $clock;

    public function __construct(ClockInterface $clock = new Clock(), array $defaultTags = [])
    {
        $this->clock = $clock;

        $this->reset();
        $this->setTagNormalizer(new NoopTagNormalizer());
        $this->setDefaultTags($defaultTags);
    }

    public function timing(string $stat, float $durationMs, float $sampleRate = 1.0, array $tags = []): void
    {
        $this->stats->timing[] = new InMemoryTimingRecord(
            $stat,
            $durationMs,
            $sampleRate,
            $this->normalizeTags($this->mergeTags($tags)),
            $this->clock->now()
        );
    }

    public function gauge(string $stat, float $value, float $sampleRate = 1.0, array $tags = []): void
    {
        $this->stats->gauge[] = new InMemoryGaugeRecord(
            $stat,
            $value,
            $sampleRate,
            $this->normalizeTags($this->mergeTags($tags)),
            $this->clock->now()
        );
    }

    public function histogram(string $stat, float $value, float $sampleRate = 1.0, array $tags = []): void
    {
        $this->stats->histogram[] = new InMemoryHistogramRecord(
            $stat,
            $value,
            $sampleRate,
            $this->normalizeTags($this->mergeTags($tags)),
            $this->clock->now()
        );
    }

    public function distribution(string $stat, float $value, float $sampleRate = 1.0, array $tags = []): void
    {
        $this->stats->distribution[] = new InMemoryDistributionRecord(
            $stat,
            $value,
            $sampleRate,
            $this->normalizeTags($this->mergeTags($tags)),
            $this->clock->now()
        );
    }

    public function set(string $stat, float|string $value, float $sampleRate = 1.0, array $tags = []): void
    {
        $this->stats->set[] = new InMemorySetRecord(
            $stat,
            $value,
            $sampleRate,
            $this->normalizeTags($this->mergeTags($tags)),
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
            $this->stats->count[] = new InMemoryCountRecord(
                $stat,
                $delta,
                $sampleRate,
                $this->normalizeTags($this->mergeTags($tags)),
                $now
            );
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

    public function getClient(): null
    {
        return null;
    }
}
