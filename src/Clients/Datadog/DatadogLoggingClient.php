<?php

namespace Cosmastech\StatsDClientAdapter\Clients\Datadog;

use DataDog\DogStatsd;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

class DatadogLoggingClient extends DogStatsd
{
    /**
     * @param  LoggerInterface  $logger
     * @param  string  $logLevel
     * @param  array<string, mixed>  $datadogConfig
     */
    public function __construct(
        protected readonly LoggerInterface $logger,
        protected readonly string $logLevel = LogLevel::DEBUG,
        array $datadogConfig = [],
    ) {
        parent::__construct($datadogConfig);
    }

    /**
     * @param mixed $message
     * @return void
     */
    public function flush($message)
    {
        $this->logger->log($this->logLevel, $message);
    }
}
