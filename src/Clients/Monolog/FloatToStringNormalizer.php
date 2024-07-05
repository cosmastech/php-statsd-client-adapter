<?php

namespace Cosmastech\StatsDClient\Clients\Monolog;

class FloatToStringNormalizer
{
    public function __construct(private readonly int $decimalPrecision = 2)
    {
    }

    /**
     * Taken from DogStatsd@normalizeValue()
     *
     * @param $value
     * @return string
     */
    public function normalize($value): string
    {
        return rtrim(rtrim(number_format((float) $value, $this->decimalPrecision, '.', ''), "0"), ".");

    }
}
