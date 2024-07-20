<?php

namespace Cosmastech\StatsDClientAdapter\Adapters\Concerns;

trait TimeClosureTrait
{
    /**
     * @inheritDoc
     */
    public function time(callable $closure, string|\UnitEnum $stat, float $sampleRate = 1.0, array $tags = [])
    {
        $startTime = intval($this->clock->now()->format("Uv"));

        $result = $closure();

        $this->timing(
            $stat,
            (intval($this->clock->now()->format("Uv")) - $startTime),
            $sampleRate,
            $tags
        );

        return $result;
    }
}
