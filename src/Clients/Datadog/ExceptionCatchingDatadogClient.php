<?php

namespace Cosmastech\StatsDClientAdapter\Clients\Datadog;

use Closure;
use DataDog\DogStatsd;
use Throwable;

class ExceptionCatchingDatadogClient extends DogStatsd
{
    /**
     * The callback to execute when there is an exception flushing stats to DataDog.
     *
     * @var Closure(Throwable, mixed): void
     */
    protected Closure $onExceptionCallback;

    /**
     * @{inheritDoc}
     *
     * @param  (Closure(\Throwable, mixed): void)  $onExceptionCallback The callback to execute when there is an exception flushing stats to DataDog
     */
    public function __construct(
        array $config,
        Closure $onExceptionCallback,
    ) {
        parent::__construct($config);

        $this->onExceptionCallback = $onExceptionCallback;
    }

    /**
     * @param  mixed $message
     * @return void
     */
    #[\Override]
    public function report($message)
    {
        try {
            parent::report($message);
        } catch (Throwable $exception) {
            call_user_func($this->onExceptionCallback, $exception, $message);
        }
    }
}
