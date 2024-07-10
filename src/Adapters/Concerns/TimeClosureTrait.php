<?php

namespace Cosmastech\StatsDClientAdapter\Adapters\Concerns;

trait TimeClosureTrait
{
    /**
     * @inheritDoc
     */
    public function time(string $stat, callable $closure, float $sampleRate = 1.0, array $tags = [])
    {
        $startTime = microtime(true);

        $result = $closure();

        $this->timing(
            $stat,
            (microtime(true) - $startTime) * 1000,
            $sampleRate,
            $tags
        );

        return $result;
    }
}
