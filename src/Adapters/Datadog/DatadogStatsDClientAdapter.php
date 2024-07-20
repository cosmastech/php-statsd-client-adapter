<?php

namespace Cosmastech\StatsDClientAdapter\Adapters\Datadog;

use Cosmastech\StatsDClientAdapter\Adapters\Concerns\HasDefaultTagsTrait;
use Cosmastech\StatsDClientAdapter\Adapters\Concerns\TagNormalizerAwareTrait;
use Cosmastech\StatsDClientAdapter\Adapters\Concerns\TimeClosureTrait;
use Cosmastech\StatsDClientAdapter\Adapters\Contracts\TagNormalizerAware;
use Cosmastech\StatsDClientAdapter\Adapters\StatsDClientAdapter;
use Cosmastech\StatsDClientAdapter\TagNormalizers\NoopTagNormalizer;
use Cosmastech\StatsDClientAdapter\TagNormalizers\TagNormalizer;
use Cosmastech\StatsDClientAdapter\Utility\Clock;
use DataDog\DogStatsd;
use Psr\Clock\ClockInterface;

class DatadogStatsDClientAdapter implements StatsDClientAdapter, TagNormalizerAware
{
    use HasDefaultTagsTrait;
    use TagNormalizerAwareTrait;
    use TimeClosureTrait;

    protected readonly DogStatsd $datadogClient;

    protected readonly ClockInterface $clock;

    /**
     * @param  DogStatsd  $datadogClient
     * @param  array<mixed, mixed>  $defaultTags
     * @param  TagNormalizer  $tagNormalizer
     * @param  ClockInterface  $clock
     */
    public function __construct(
        DogStatsd $datadogClient,
        array $defaultTags = [],
        TagNormalizer $tagNormalizer = new NoopTagNormalizer(),
        ClockInterface $clock = new Clock(),
    ) {
        $this->datadogClient = $datadogClient;
        $this->setDefaultTags($defaultTags);
        $this->setTagNormalizer($tagNormalizer);
        $this->clock = $clock;
    }

    /**
     * @inheritDoc
     */
    public function timing(string|\UnitEnum $stat, float $durationMs, float $sampleRate = 1.0, array $tags = []): void
    {
        $this->datadogClient->timing(
            $stat,
            $durationMs,
            $sampleRate,
            $this->normalizeTags($this->mergeWithDefaultTags($tags))
        );
    }

    /**
     * @inheritDoc
     */
    public function gauge(string|\UnitEnum $stat, float $value, float $sampleRate = 1.0, array $tags = []): void
    {
        $this->datadogClient->gauge(
            $stat,
            $value,
            $sampleRate,
            $this->normalizeTags($this->mergeWithDefaultTags($tags))
        );
    }

    /**
     * @inheritDoc
     */
    public function histogram(string|\UnitEnum $stat, float $value, float $sampleRate = 1.0, array $tags = []): void
    {
        $this->datadogClient->histogram(
            $stat,
            $value,
            $sampleRate,
            $this->normalizeTags($this->mergeWithDefaultTags($tags))
        );
    }

    /**
     * @inheritDoc
     */
    public function distribution(string|\UnitEnum $stat, float $value, float $sampleRate = 1.0, array $tags = []): void
    {
        $this->datadogClient->distribution(
            $stat,
            $value,
            $sampleRate,
            $this->normalizeTags($this->mergeWithDefaultTags($tags))
        );
    }

    /**
     * @inheritDoc
     */
    public function set(string|\UnitEnum $stat, float|string $value, float $sampleRate = 1.0, array $tags = []): void
    {
        $this->datadogClient->set(
            $stat,
            $value,
            $sampleRate,
            $this->normalizeTags($this->mergeWithDefaultTags($tags))
        );
    }

    /**
     * @inheritDoc
     */
    public function increment(array|string|\UnitEnum $stats, float $sampleRate = 1.0, array $tags = [], int $value = 1): void
    {
        $this->datadogClient->increment(
            $stats,
            $sampleRate,
            $this->normalizeTags($this->mergeWithDefaultTags($tags)),
            $value
        );
    }

    /**
     * @inheritDoc
     */
    public function decrement(array|string|\UnitEnum $stats, float $sampleRate = 1.0, array $tags = [], int $value = -1): void
    {
        $this->datadogClient->decrement(
            $stats,
            $sampleRate,
            $this->normalizeTags($this->mergeWithDefaultTags($tags)),
            $value
        );
    }

    /**
     * @inheritDoc
     */
    public function updateStats(array|string|\UnitEnum $stats, int $delta = 1, $sampleRate = 1.0, array $tags = null): void
    {
        $this->datadogClient->updateStats(
            $stats,
            $delta,
            $sampleRate,
            $this->normalizeTags($this->mergeWithDefaultTags($tags))
        );
    }

    /**
     * @inheritDoc
     */
    public function getClient(): DogStatsd
    {
        return $this->datadogClient;
    }
}
