<?php

namespace Cosmastech\StatsDClient\Utility\SampleRateDecider;

use Cosmastech\StatsDClient\Utility\SampleRateDecider\Contracts\SampleRateSendDecider as SampleRateSendDeciderInterface;

class SampleRateSendDecider implements SampleRateSendDeciderInterface
{
    public function decide(float $sampleRate): bool
    {
        if ($sampleRate >= 1) {
            return true;
        }

        if ((mt_rand() / mt_getrandmax()) <= $sampleRate) {
            return true;
        }

        return false;
    }
}
