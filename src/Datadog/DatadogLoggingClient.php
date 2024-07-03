<?php

namespace Cosmastech\StatsDClient\Datadog;

use DataDog\DogStatsd;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

class DatadogLoggingClient extends DogStatsd
{
    public function __construct(
        protected readonly LoggerInterface $logger,
        protected readonly string $logLevel = LogLevel::DEBUG,
        array $datadogConfig = [],
    ) {
        parent::__construct($datadogConfig);
    }

    public function flush($message)
    {
        $this->logger->log($this->logLevel, $message);
    }
}
