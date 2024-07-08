<?php

namespace Cosmastech\StatsDClientAdapter\Adapters;

interface StatsDClientAdapter
{
    public function timing(string $stat, float $durationMs, float $sampleRate = 1.0, array $tags = []): void;

    public function gauge(string $stat, float $value, float $sampleRate = 1.0, array $tags = []): void;

    public function histogram(string $stat, float $value, float $sampleRate = 1.0, array $tags = []): void;

    public function distribution(string $stat, float $value, float $sampleRate = 1.0, array $tags = []): void;

    public function set(string $stat, float|string $value, float $sampleRate = 1.0, array $tags = []): void;

    public function increment(array|string $stats, float $sampleRate = 1.0, array $tags = [], int $value = 1): void;

    public function decrement(array|string $stats, float $sampleRate = 1.0, array $tags = [], int $value = 1): void;

    public function updateStats(array|string $stats, int $delta = 1, $sampleRate = 1.0, $tags = null): void;

    public function getClient(): mixed;

    /**
     * @param  array<string, mixed>  $tags
     * @return void
     */
    public function setDefaultTags(array $tags = []): void;

    /**
     * @return array<string, mixed>
     */
    public function getDefaultTags(): array;
}
