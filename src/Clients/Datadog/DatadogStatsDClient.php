<?php

namespace Cosmastech\StatsDClient\Clients\Datadog;

use Cosmastech\StatsDClient\Clients\Concerns\TagNormalizerAwareTrait;
use Cosmastech\StatsDClient\Clients\Contracts\TagNormalizerAware;
use Cosmastech\StatsDClient\Clients\StatsDClient;
use Cosmastech\StatsDClient\TagNormalizers\NoopTagNormalizer;
use DataDog\DogStatsd;

class DatadogStatsDClient implements StatsDClient, TagNormalizerAware
{
    use TagNormalizerAwareTrait;

    protected function __construct(protected readonly DogStatsd $datadogClient)
    {
        $this->tagNormalizer = new NoopTagNormalizer();
    }

    public static function fromConfig(array $config): static
    {
        return new static(new DogStatsd($config));
    }


    public function timing(string $stat, float $durationMs, float $sampleRate = 1.0, array $tags = []): void
    {
        $this->datadogClient->timing($stat, $durationMs, $sampleRate, $this->normalizeTags($tags));
    }

    public function gauge(string $stat, float $value, float $sampleRate = 1.0, array $tags = []): void
    {
        $this->datadogClient->gauge($stat, $value, $sampleRate, $this->normalizeTags($tags));
    }

    public function histogram(string $stat, float $value, float $sampleRate = 1.0, array $tags = []): void
    {
        $this->datadogClient->histogram($stat, $value, $sampleRate, $this->normalizeTags($tags));
    }

    public function distribution(string $stat, float $value, float $sampleRate = 1.0, array $tags = []): void
    {
        $this->datadogClient->distribution($stat, $value, $sampleRate, $this->normalizeTags($tags));
    }

    public function set(string $stat, float|string $value, float $sampleRate = 1.0, array $tags = []): void
    {
        $this->datadogClient->set($stat, $value, $sampleRate, $this->normalizeTags($tags));
    }

    public function increment(array|string $stats, float $sampleRate = 1.0, array $tags = [], int $value = 1): void
    {
        $this->datadogClient->increment($stats, $sampleRate, $this->normalizeTags($tags), $value);
    }

    public function decrement(array|string $stats, float $sampleRate = 1.0, array $tags = [], int $value = -1): void
    {
        $this->datadogClient->decrement($stats, $sampleRate, $this->normalizeTags($tags), $value);
    }

    public function updateStats(array|string $stats, int $delta = 1, $sampleRate = 1.0, $tags = null): void
    {
        $this->datadogClient->updateStats($stats, $delta, $sampleRate, $this->normalizeTags($tags));
    }
}