# StatsD Client Adapter
This package was originally designed to solve the problem of:
* I use DataDog on production, but
* I don't want to push stats to DataDog on my dev environments

Where might I want to push those precious stats? Maybe to a log? Maybe to a locally running [StatsD server](https://github.com/statsd/statsd)?

While [PHP League's statsd package](https://github.com/thephpleague/statsd) is great, it doesn't allow for sending stats to DataDog
(such as [histogram](https://docs.datadoghq.com/metrics/types/?tab=histogram) or [distribution](https://docs.datadoghq.com/metrics/types/?tab=distribution)).
Nor does the DataDog client allow for pushing to another StatsD implementation easily.

The aim here is to allow for a single interface that can wrap around both, and be easily extended for different implementations.

## Gotchas
1. Only increment/decrement on PHPLeague's implementation allow for including the sample rate. If you are using a sample rate with other calls, their sample rate will not be included as part of the stat.
2. There are `histogram()` and `distribution()` methods on `LeagueStatsDClientAdapter`, but they only raise a PHP error and are no-op.
