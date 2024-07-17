<?php

namespace Cosmastech\StatsDClientAdapter\Adapters\InMemory;

use Cosmastech\StatsDClientAdapter\Adapters\Concerns\HasDefaultTagsTrait;
use Cosmastech\StatsDClientAdapter\Adapters\Concerns\TagNormalizerAwareTrait;
use Cosmastech\StatsDClientAdapter\Adapters\Concerns\TimeClosureTrait;
use Cosmastech\StatsDClientAdapter\Adapters\Contracts\TagNormalizerAware;
use Cosmastech\StatsDClientAdapter\Adapters\InMemory\Models\Contracts\InMemoryStatsRecordInterface;
use Cosmastech\StatsDClientAdapter\Adapters\InMemory\Models\InMemoryCountRecord;
use Cosmastech\StatsDClientAdapter\Adapters\InMemory\Models\InMemoryDistributionRecord;
use Cosmastech\StatsDClientAdapter\Adapters\InMemory\Models\InMemoryGaugeRecord;
use Cosmastech\StatsDClientAdapter\Adapters\InMemory\Models\InMemoryHistogramRecord;
use Cosmastech\StatsDClientAdapter\Adapters\InMemory\Models\InMemorySetRecord;
use Cosmastech\StatsDClientAdapter\Adapters\InMemory\Models\InMemoryStatsRecord;
use Cosmastech\StatsDClientAdapter\Adapters\InMemory\Models\InMemoryTimingRecord;
use Cosmastech\StatsDClientAdapter\Adapters\StatsDClientAdapter;
use Cosmastech\StatsDClientAdapter\TagNormalizers\NoopTagNormalizer;
use Cosmastech\StatsDClientAdapter\TagNormalizers\TagNormalizer;
use Cosmastech\StatsDClientAdapter\Utility\Clock;
use Psr\Clock\ClockInterface;

class InMemoryClientAdapter implements StatsDClientAdapter, TagNormalizerAware
{
    use HasDefaultTagsTrait;
    use TagNormalizerAwareTrait;
    use TimeClosureTrait;

    protected readonly InMemoryStatsRecord $stats;

    protected readonly ClockInterface $clock;

    /**
     * @param  array<mixed, mixed>  $defaultTags
     * @param  InMemoryStatsRecordInterface  $inMemoryStatsRecord
     * @param  TagNormalizer  $tagNormalizer
     * @param  ClockInterface  $clock
     */
    public function __construct(
        array $defaultTags = [],
        InMemoryStatsRecordInterface $inMemoryStatsRecord = new InMemoryStatsRecord(),
        TagNormalizer $tagNormalizer = new NoopTagNormalizer(),
        ClockInterface $clock = new Clock()
    ) {
        $this->setDefaultTags($defaultTags);
        $this->stats = $inMemoryStatsRecord;
        $this->setTagNormalizer($tagNormalizer);
        $this->clock = $clock;
    }

    /**
     * Clear stats from memory.
     */
    public function flush(): void
    {
        $this->stats->flush();
    }

    /**
     * @inheritDoc
     */
    public function timing(string $stat, float $durationMs, float $sampleRate = 1.0, array $tags = []): void
    {
        $this->stats->recordTiming(
            new InMemoryTimingRecord(
                $stat,
                $durationMs,
                $sampleRate,
                $this->normalizeTags($this->mergeWithDefaultTags($tags)),
                $this->clock->now()
            )
        );
    }

    /**
     * @inheritDoc
     */
    public function gauge(string $stat, float $value, float $sampleRate = 1.0, array $tags = []): void
    {
        $this->stats->recordGauge(
            new InMemoryGaugeRecord(
                $stat,
                $value,
                $sampleRate,
                $this->normalizeTags($this->mergeWithDefaultTags($tags)),
                $this->clock->now()
            )
        );
    }

    /**
     * @inheritDoc
     */
    public function histogram(string $stat, float $value, float $sampleRate = 1.0, array $tags = []): void
    {
        $this->stats->recordHistogram(
            new InMemoryHistogramRecord(
                $stat,
                $value,
                $sampleRate,
                $this->normalizeTags($this->mergeWithDefaultTags($tags)),
                $this->clock->now()
            )
        );
    }

    /**
     * @inheritDoc
     */
    public function distribution(string $stat, float $value, float $sampleRate = 1.0, array $tags = []): void
    {
        $this->stats->recordDistribution(
            new InMemoryDistributionRecord(
                $stat,
                $value,
                $sampleRate,
                $this->normalizeTags($this->mergeWithDefaultTags($tags)),
                $this->clock->now()
            )
        );
    }

    /**
     * @inheritDoc
     */
    public function set(string $stat, float|string $value, float $sampleRate = 1.0, array $tags = []): void
    {
        $this->stats->recordSet(
            new InMemorySetRecord(
                $stat,
                $value,
                $sampleRate,
                $this->normalizeTags($this->mergeWithDefaultTags($tags)),
                $this->clock->now()
            )
        );
    }

    /**
     * @inheritDoc
     */
    public function increment(array|string $stats, float $sampleRate = 1.0, array $tags = [], int $value = 1): void
    {
        $this->updateStats($stats, $value, $sampleRate, $tags);
    }

    /**
     * @inheritDoc
     */
    public function decrement(array|string $stats, float $sampleRate = 1.0, array $tags = [], int $value = -1): void
    {
        if ($value > 0) {
            $value *= -1;
        }

        $this->updateStats($stats, $value, $sampleRate, $tags);
    }

    /**
     * @inheritDoc
     */
    public function updateStats(array|string $stats, int $delta = 1, float $sampleRate = 1.0, array $tags = []): void
    {
        $stats = (array) $stats;
        $now = $this->clock->now();

        foreach ($stats as $stat) {
            $this->stats->recordCount(
                new InMemoryCountRecord(
                    $stat,
                    $delta,
                    $sampleRate,
                    $this->normalizeTags($this->mergeWithDefaultTags($tags)),
                    $now
                )
            );
        }
    }

    /**
     * Get all recorded stats.
     */
    public function getStats(): InMemoryStatsRecordInterface
    {
        return $this->stats;
    }

    /**
     * @inheritDoc
     */
    public function getClient(): null
    {
        return null;
    }
}
