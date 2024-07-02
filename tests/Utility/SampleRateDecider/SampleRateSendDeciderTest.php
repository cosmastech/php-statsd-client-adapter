<?php

namespace Cosmastech\StatsDClient\Tests\Utility\SampleRateDecider;

use Cosmastech\StatsDClient\Tests\BaseTestCase;
use Cosmastech\StatsDClient\Utility\SampleRateDecider\SampleRateSendDecider;
use PHPUnit\Framework\Attributes\Test;

class SampleRateSendDeciderTest extends BaseTestCase
{
    #[Test]
    public function decide_sampleRateEquals1_returnsTrue()
    {
        // Given
        $sampleRate = 1;

        // When
        $actual = (new SampleRateSendDecider())->decide($sampleRate);

        // Then
        self::assertTrue($actual);
    }

    #[Test]
    public function decide_sampleRateAbove1_returnsTrue()
    {
        // Given
        $sampleRate = 1.01;

        // When
        $actual = (new SampleRateSendDecider())->decide($sampleRate);

        // Then
        self::assertTrue($actual);
    }

    #[Test]
    public function decide_sampleRateEquals0_returnsFalse()
    {
        // Given
        $sampleRate = 0.0;

        // When
        $actual = (new SampleRateSendDecider())->decide($sampleRate);

        // Then
        self::assertFalse($actual);
    }

    #[Test]
    public function decide_sampleRateBelow0_returnsFalse()
    {
        // Given
        $sampleRate = -110.0;

        // When
        $actual = (new SampleRateSendDecider())->decide($sampleRate);

        // Then
        self::assertFalse($actual);
    }
}
