<?php

namespace Cosmastech\StatsDClientAdapter\Tests\Doubles;

use DataDog\DogStatsd;

class DogStatsDSpy extends DogStatsd
{
    /** @var array<int, array<string, mixed>> */
    public array $timings = [];

    /** @var array<int, array<string, mixed>> */
    public array $gauges = [];

    /** @var array<int, array<string, mixed>> */
    public array $histograms = [];

    /** @var array<int, array<string, mixed>> */
    public array $distributions = [];

    /** @var array<int, array<string, mixed>> */
    public array $sets = [];

    /** @var array<int, array<string, mixed>> */
    public array $increments = [];

    /** @var array<int, array<string, mixed>> */
    public array $decrements = [];

    public function __construct()
    {
        // we do not need any of the parent constructor functionality
    }

    /**
     * @inheritDoc
     * @phpstan-ignore missingType.iterableValue
     */
    public function timing($stat, $time, $sampleRate = 1.0, $tags = null)
    {
        $this->timings[] = compact('stat', 'time', 'sampleRate', 'tags');
    }

    /**
     * @inheritDoc
     * @phpstan-ignore missingType.iterableValue
     */
    public function gauge($stat, $value, $sampleRate = 1.0, $tags = null)
    {
        $this->gauges[] = compact('stat', 'value', 'sampleRate', 'tags');
    }

    /**
     * @inheritDoc
     * @phpstan-ignore missingType.iterableValue
     */
    public function histogram($stat, $value, $sampleRate = 1.0, $tags = null)
    {
        $this->histograms[] = compact('stat', 'value', 'sampleRate', 'tags');
    }

    /**
     * @inheritDoc
     * @phpstan-ignore missingType.iterableValue
     */
    public function distribution($stat, $value, $sampleRate = 1.0, $tags = null)
    {
        $this->distributions[] = compact('stat', 'value', 'sampleRate', 'tags');
    }

    /**
     * @inheritdoc
     * @phpstan-ignore missingType.iterableValue
     */
    public function set($stat, $value, $sampleRate = 1.0, $tags = null)
    {
        $this->sets[] = compact('stat', 'value', 'sampleRate', 'tags');
    }

    /**
     * @inheritDoc
     * @param  string|array<int, mixed> $stats
     * @param  array<int, mixed>|string $tags
     */
    public function increment(
        $stats,
        $sampleRate = 1.0,
        $tags = null,
        $value = 1
    ) {
        $this->increments[] = compact('stats', 'value', 'sampleRate', 'tags');
    }

    /**
     * @inheritdoc
     * @param  string|array<int, mixed>  $stats
     * @param  array<int, mixed>|string  $tags
     */
    public function decrement($stats, $sampleRate = 1.0, $tags = null, $value = -1)
    {
        $this->decrements[] = compact('stats', 'value', 'sampleRate', 'tags');
    }
}
