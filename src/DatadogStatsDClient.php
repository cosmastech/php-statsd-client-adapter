<?php

namespace Cosmastech\StatsDClient;

use Cosmastech\StatsDClient\Concerns\NormalizesTags;
use DataDog\DogStatsd;

class DatadogStatsDClient implements StatsDClient
{
    use NormalizesTags;
    private function __construct(private readonly DogStatsd $datadogClient)
    {
    }

    public static function fromConfig(array $config): static
    {
        return new static(new DogStatsd($config));
    }


    public function timing(string $stat, float $time, float $sampleRate = 1.0, array $tags = []): void
    {
        $this->datadogClient->timing($stat, $time, $sampleRate, $this->normalizeTags($tags));
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