<?php

require_once __DIR__ . "/../vendor/autoload.php";

// See instantiation parameters: https://docs.datadoghq.com/developers/dogstatsd/?code-lang=php&tab=hostagent#client-instantiation-parameters

$datadog = new \Datadog\DogStatsd();

$adapter = new \Cosmastech\StatsDClientAdapter\Adapters\Datadog\DatadogStatsDClientAdapter($datadog);

$adapter->histogram("my-histogram", 11.2);

// Check DataDog and see that this histogram is recorded