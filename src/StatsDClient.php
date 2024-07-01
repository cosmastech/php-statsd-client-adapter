<?php

namespace Cosmastech\DatadogStatsLaravel;

interface StatsDClient
{
    public function timing(string $stat, float $time, float $sampleRate = 1.0, array $tags = []): void;

    public function microTiming(string $stat, float $time, float $sampleRate = 1.0, array $tags = []): void;

    public function gauge(string $stat, float $value, float $sampleRate = 1.0, array $tags = []): void;

    public function histogram(string $stat, float $value, float $sampleRate = 1.0, array $tags = []): void;

    public function distribution(string $stat, float $value, float $sampleRate = 1.0, array $tags = []): void;

    public function set(string $stat, float|string $value, float $sampleRate = 1.0, array $tags = []): void;

    public function increment(array|string $stats, float $sampleRate = 1.0, array $tags = [], int $value = 1): void;

    public function decrement(array|string $stats, float $sampleRate = 1.0, array $tags = [], int $value = 1): void;

    public function updateStats(array|string $stats, int $delta = 1, $sampleRate = 1.0, $tags = null): void;


}