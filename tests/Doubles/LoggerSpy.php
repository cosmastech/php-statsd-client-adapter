<?php

namespace Cosmastech\StatsDClient\Tests\Doubles;

use Psr\Log\LoggerInterface;
use Psr\Log\LoggerTrait;
use Stringable;

class LoggerSpy implements LoggerInterface
{
    use LoggerTrait;

    private array $logs = [];

    public function __construct()
    {
    }

    public function getLogs(): array
    {
        return $this->logs;
    }

    /**
     * @inheritDoc
     */
    public function log($level, Stringable|string $message, array $context = []): void
    {
        $this->logs[] = [
            "level" => $level,
            "message" => $message,
            "context" => $context,
        ];
    }
}
