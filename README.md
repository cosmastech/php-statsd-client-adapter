[![Latest Stable Version](http://poser.pugx.org/cosmastech/statsd-client-adapter/v)](https://packagist.org/packages/cosmastech/statsd-client-adapter) [![Total Downloads](http://poser.pugx.org/cosmastech/statsd-client-adapter/downloads)](https://packagist.org/packages/cosmastech/statsd-client-adapter)  [![License](http://poser.pugx.org/cosmastech/statsd-client-adapter/license)](https://packagist.org/packages/cosmastech/statsd-client-adapter) [![PHP Version Require](http://poser.pugx.org/cosmastech/statsd-client-adapter/require/php)](https://packagist.org/packages/cosmastech/statsd-client-adapter)


# StatsD Client Adapter
This package was originally designed to solve the problem of:
* I use DataDog on production, but
* I don't want to push stats to DataDog on my dev or test environments

Where might I want to push those precious stats? Maybe to a log? Maybe to a locally running [StatsD server](https://github.com/statsd/statsd)?
What if in my unit tests, I want to confirm that logs are being pushed, but not go through the hassle of an integration
test set up that configures the StatsD server?

While [PHP League's statsd package](https://github.com/thephpleague/statsd) is great, it doesn't allow for sending DataDog specific stats 
(such as [histogram](https://docs.datadoghq.com/metrics/types/?tab=histogram) or [distribution](https://docs.datadoghq.com/metrics/types/?tab=distribution)).
Nor does the DataDog client allow for pushing to another StatsD implementation easily.

The aim here is to allow for a single interface that can wrap around both, and be easily extended for different implementations.


## Adapters

### InMemoryClientAdapter
This adapter simply records your stats in an object in memory. This is best served as a way to verify stats are recorded in your unit tests.

See [examples/in_memory.php](examples/in_memory.php) for how you might implement this.

### DataDogStatsDClientAdapter
This is a wrapper around DataDog's [php-datadogstatsd](https://github.com/dataDog/php-datadogstatsd/) client.

If you wish to use this adapter, please make sure you install the php-datadogstatsd client.

```shell
composer require datadog/php-datadogstatsd
```

For specifics on their configuration, see the [official DogStatsD documentation](https://docs.datadoghq.com/developers/dogstatsd/?code-lang=php&tab=hostagent#client-instantiation-parameters).

See [examples/datadog.php](examples/datadog.php) for how you might implement this.

### DatadogLoggingClient
Envisioned as a client for local development, this adapter writes to a class which implements the [psr-logger interface](https://packagist.org/packages/psr/log).
You can find a [list](https://packagist.org/providers/psr/log-implementation) of packages that implement the interface on packagist.
If you are using a framework like Symfony or Laravel, then you already have one of the most popular and reliable implementations installed: [monolog/monolog](https://github.com/Seldaek/monolog).

For a local development setup, you could just write the stats to a log. This writes the format exactly as it would be sent to DataDog.

See [examples/log_datadog.php](examples/log_datadog.php) for how you might implement this.


## Gotchas
1. Only increment/decrement on DataDog's implementation allow for including the sample rate. If you are using a sample rate with other calls, their sample rate will not be included as part of the stat.
2. There are `histogram()` and `distribution()` methods on `LeagueStatsDClientAdapter`, but they only raise a PHP error and are no-op.


## Testing
```shell
composer test
```