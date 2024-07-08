<?php

namespace Cosmastech\StatsDClientAdapter\Utility;

use DateTimeImmutable;
use Psr\Clock\ClockInterface;

/**
 * The simplest possible interface: always return the current time.
 */
class Clock implements ClockInterface
{
    /**
     * @inheritDoc
     */
    public function now(): DateTimeImmutable
    {
        return new DateTimeImmutable();
    }
}
