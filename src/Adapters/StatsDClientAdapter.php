<?php

namespace Cosmastech\StatsDClientAdapter\Adapters;

use UnitEnum;

interface StatsDClientAdapter
{
    /**
     * @param  string|UnitEnum  $stat
     * @param  float  $durationMs
     * @param  float  $sampleRate
     * @param  array<mixed, mixed>  $tags
     * @return void
     */
    public function timing(string|UnitEnum $stat, float $durationMs, float $sampleRate = 1.0, array $tags = []): void;

    /**
     * Record a timing stat for the duration of the $closure.
     *
     * @param  callable  $closure
     * @param  string|UnitEnum  $stat
     * @param  float  $sampleRate
     * @param  array<mixed, mixed>  $tags
     * @return mixed The return value of $closure
     */
    public function time(callable $closure, string|UnitEnum $stat, float $sampleRate = 1.0, array $tags = []);

    /**
     * @param  string|UnitEnum  $stat
     * @param  float  $value
     * @param  float  $sampleRate
     * @param  array<mixed, mixed>  $tags
     * @return void
     */
    public function gauge(string|UnitEnum $stat, float $value, float $sampleRate = 1.0, array $tags = []): void;

    /**
     * @param  string|UnitEnum  $stat
     * @param  float  $value
     * @param  float  $sampleRate
     * @param  array<mixed, mixed>  $tags
     * @return void
     */
    public function histogram(string|UnitEnum $stat, float $value, float $sampleRate = 1.0, array $tags = []): void;

    /**
     * @param  string|UnitEnum  $stat
     * @param  float  $value
     * @param  float  $sampleRate
     * @param  array<mixed, mixed>  $tags
     * @return void
     */
    public function distribution(string|UnitEnum $stat, float $value, float $sampleRate = 1.0, array $tags = []): void;

    /**
     * @param  string|UnitEnum  $stat
     * @param  float|string  $value
     * @param  float  $sampleRate
     * @param  array<mixed, mixed>  $tags
     * @return void
     */
    public function set(string|UnitEnum $stat, float|string $value, float $sampleRate = 1.0, array $tags = []): void;

    /**
     * @param  string|UnitEnum|array<int, string|UnitEnum>  $stats
     * @param  float  $sampleRate
     * @param  array<mixed, mixed>  $tags
     * @param  int  $value
     * @return void
     */
    public function increment(array|string|UnitEnum $stats, float $sampleRate = 1.0, array $tags = [], int $value = 1): void;

    /**
     * @param  string|UnitEnum|array<int, string|UnitEnum>  $stats
     * @param  float  $sampleRate
     * @param  array<mixed, mixed>  $tags
     * @param  int  $value
     * @return void
     */
    public function decrement(array|string|UnitEnum $stats, float $sampleRate = 1.0, array $tags = [], int $value = 1): void;

    /**
     * @param  string|UnitEnum|array<int, string|UnitEnum>  $stats
     * @param  int  $delta
     * @param  float $sampleRate
     * @param  array<mixed, mixed> $tags
     * @return void
     */
    public function updateStats(array|string|UnitEnum $stats, int $delta = 1, float $sampleRate = 1.0, array $tags = []): void;

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
