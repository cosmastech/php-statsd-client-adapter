<?php

namespace Cosmastech\StatsDClientAdapter\Adapters;

interface StatsDClientAdapter
{
    /**
     * @param  string  $stat
     * @param  float  $durationMs
     * @param  float  $sampleRate
     * @param  array<mixed, mixed>  $tags
     * @return void
     */
    public function timing(string $stat, float $durationMs, float $sampleRate = 1.0, array $tags = []): void;

    /**
     * Record a timing stat for the duration of the $closure.
     *
     * @param  string  $stat
     * @param  callable  $closure
     * @param  float  $sampleRate
     * @param  array<mixed, mixed>  $tags
     * @return mixed The return value of $closure
     */
    public function time(string $stat, callable $closure, float $sampleRate = 1.0, array $tags = []);

    /**
     * @param  string  $stat
     * @param  float  $value
     * @param  float  $sampleRate
     * @param  array<mixed, mixed>  $tags
     * @return void
     */
    public function gauge(string $stat, float $value, float $sampleRate = 1.0, array $tags = []): void;

    /**
     * @param  string  $stat
     * @param  float  $value
     * @param  float  $sampleRate
     * @param  array<mixed, mixed>  $tags
     * @return void
     */
    public function histogram(string $stat, float $value, float $sampleRate = 1.0, array $tags = []): void;

    /**
     * @param  string  $stat
     * @param  float  $value
     * @param  float  $sampleRate
     * @param  array<mixed, mixed>  $tags
     * @return void
     */
    public function distribution(string $stat, float $value, float $sampleRate = 1.0, array $tags = []): void;

    /**
     * @param  string  $stat
     * @param  float|string  $value
     * @param  float  $sampleRate
     * @param  array<mixed, mixed>  $tags
     * @return void
     */
    public function set(string $stat, float|string $value, float $sampleRate = 1.0, array $tags = []): void;

    /**
     * @param  array<int, string>|string  $stats
     * @param  float  $sampleRate
     * @param  array<mixed, mixed>  $tags
     * @param  int  $value
     * @return void
     */
    public function increment(array|string $stats, float $sampleRate = 1.0, array $tags = [], int $value = 1): void;

    /**
     * @param  array<int, string>|string  $stats
     * @param  float  $sampleRate
     * @param  array<mixed, mixed>  $tags
     * @param  int  $value
     * @return void
     */
    public function decrement(array|string $stats, float $sampleRate = 1.0, array $tags = [], int $value = 1): void;

    /**
     * @param  array<int, string>|string  $stats
     * @param  int  $delta
     * @param  float $sampleRate
     * @param  array<mixed, mixed> $tags
     * @return void
     */
    public function updateStats(array|string $stats, int $delta = 1, float $sampleRate = 1.0, array $tags = []): void;

    /**
     * Returning underlying client.
     */
    public function getClient(): mixed;

    /**
     * @param  array<mixed, mixed>  $defaultTags
     * @return void
     */
    public function setDefaultTags(array $defaultTags = []): void;

    /**
     * @return array<mixed, mixed>
     */
    public function getDefaultTags(): array;
}
