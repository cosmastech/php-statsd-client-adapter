<?php

namespace Cosmastech\StatsDClientAdapter\Adapters\Datadog;

use Cosmastech\StatsDClientAdapter\Adapters\Concerns\HasDefaultTagsTrait;
use Cosmastech\StatsDClientAdapter\Adapters\Concerns\TagNormalizerAwareTrait;
use Cosmastech\StatsDClientAdapter\Adapters\Contracts\TagNormalizerAware;
use Cosmastech\StatsDClientAdapter\Adapters\StatsDClientAdapter;
use Cosmastech\StatsDClientAdapter\TagNormalizers\NoopTagNormalizer;
use DataDog\DogStatsd;

class DatadogStatsDClientAdapter implements StatsDClientAdapter, TagNormalizerAware
{
    use HasDefaultTagsTrait;
    use TagNormalizerAwareTrait;

    public function __construct(protected readonly DogStatsd $datadogClient, array $defaultTags = [])
    {
        $this->tagNormalizer = new NoopTagNormalizer();
        $this->setDefaultTags($defaultTags);
    }

    public function timing(string $stat, float $durationMs, float $sampleRate = 1.0, array $tags = []): void
    {
        $this->datadogClient->timing(
            $stat,
            $durationMs,
            $sampleRate,
            $this->normalizeTags($this->mergeTags($tags))
        );
    }

    public function gauge(string $stat, float $value, float $sampleRate = 1.0, array $tags = []): void
    {
        $this->datadogClient->gauge(
            $stat,
            $value,
            $sampleRate,
            $this->normalizeTags($this->mergeTags($tags))
        );
    }

    public function histogram(string $stat, float $value, float $sampleRate = 1.0, array $tags = []): void
    {
        $this->datadogClient->histogram(
            $stat,
            $value,
            $sampleRate,
            $this->normalizeTags($this->mergeTags($tags))
        );
    }

    public function distribution(string $stat, float $value, float $sampleRate = 1.0, array $tags = []): void
    {
        $this->datadogClient->distribution(
            $stat,
            $value,
            $sampleRate,
            $this->normalizeTags($this->mergeTags($tags))
        );
    }

    public function set(string $stat, float|string $value, float $sampleRate = 1.0, array $tags = []): void
    {
        $this->datadogClient->set(
            $stat,
            $value,
            $sampleRate,
            $this->normalizeTags($this->mergeTags($tags))
        );
    }

    public function increment(array|string $stats, float $sampleRate = 1.0, array $tags = [], int $value = 1): void
    {
        $this->datadogClient->increment(
            $stats,
            $sampleRate,
            $this->normalizeTags($this->mergeTags($tags)),
            $value
        );
    }

    public function decrement(array|string $stats, float $sampleRate = 1.0, array $tags = [], int $value = -1): void
    {
        $this->datadogClient->decrement(
            $stats,
            $sampleRate,
            $this->normalizeTags($this->mergeTags($tags)),
            $value
        );
    }

    public function updateStats(array|string $stats, int $delta = 1, $sampleRate = 1.0, array $tags = null): void
    {
        $this->datadogClient->updateStats(
            $stats,
            $delta,
            $sampleRate,
            $this->normalizeTags($this->mergeTags($tags))
        );
    }

    public function getClient(): DogStatsd
    {
        return $this->datadogClient;
    }
}
