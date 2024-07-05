<?php

namespace Cosmastech\StatsDClient\Clients\Monolog;

use Cosmastech\StatsDClient\Clients\Concerns\TagNormalizerAwareTrait;
use Cosmastech\StatsDClient\Clients\Contracts\TagNormalizerAware;
use Cosmastech\StatsDClient\Clients\StatsDClientAdapter;
use Cosmastech\StatsDClient\TagNormalizers\TagNormalizer;
use Cosmastech\StatsDClient\Utility\SampleRateDecider\SampleRateSendDecider;
use Monolog\Level;
use Monolog\Logger;

class MonologClientAdapter implements StatsDClientAdapter, TagNormalizerAware
{
    use TagNormalizerAwareTrait;

    public function __construct(
        protected readonly Logger $logger,
        TagNormalizer $tagNormalizer,
        protected readonly SampleRateSendDecider $sampleRateSendDecider = new SampleRateSendDecider(),
        protected readonly Level $logLevel = Level::Debug,
        protected readonly FloatToStringNormalizer $floatToStringNormalizer = new FloatToStringNormalizer(),
    ) {
        $this->setTagNormalizer($tagNormalizer);
    }

    protected function writeToLog(string $log, Level $level): void
    {

    }
    public function timing(string $stat, float $durationMs, float $sampleRate = 1.0, array $tags = []): void
    {

        $this->logger->log($this->logLevel, );
    }

    public function gauge(string $stat, float $value, float $sampleRate = 1.0, array $tags = []): void
    {
        // TODO: Implement gauge() method.
    }

    public function histogram(string $stat, float $value, float $sampleRate = 1.0, array $tags = []): void
    {
        // TODO: Implement histogram() method.
    }

    public function distribution(string $stat, float $value, float $sampleRate = 1.0, array $tags = []): void
    {
        // TODO: Implement distribution() method.
    }

    public function set(string $stat, float|string $value, float $sampleRate = 1.0, array $tags = []): void
    {
        // TODO: Implement set() method.
    }

    public function increment(array|string $stats, float $sampleRate = 1.0, array $tags = [], int $value = 1): void
    {
        // TODO: Implement increment() method.
    }

    public function decrement(array|string $stats, float $sampleRate = 1.0, array $tags = [], int $value = 1): void
    {
        // TODO: Implement decrement() method.
    }

    public function updateStats(array|string $stats, int $delta = 1, $sampleRate = 1.0, $tags = null): void
    {
        // TODO: Implement updateStats() method.
    }

    public function getClient(): mixed
    {
        // TODO: Implement getClient() method.
    }
}
