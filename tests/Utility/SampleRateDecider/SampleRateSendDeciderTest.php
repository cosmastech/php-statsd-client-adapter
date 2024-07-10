<?php

namespace Cosmastech\StatsDClientAdapter\Tests\Utility\SampleRateDecider;

use Cosmastech\StatsDClientAdapter\Tests\BaseTestCase;
use Cosmastech\StatsDClientAdapter\Utility\SampleRateDecider\SampleRateSendDecider;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;

#[CoversClass(SampleRateSendDecider::class)]
class SampleRateSendDeciderTest extends BaseTestCase
{
    #[Test]
    public function decide_sampleRateEquals1_returnsTrue(): void
    {
        // Given
        $sampleRate = 1;

        // When
        $actual = (new SampleRateSendDecider())->decide($sampleRate);

        // Then
        self::assertTrue($actual);
    }

    #[Test]
    public function decide_sampleRateAbove1_returnsTrue(): void
    {
        // Given
        $sampleRate = 1.01;

        // When
        $actual = (new SampleRateSendDecider())->decide($sampleRate);

        // Then
        self::assertTrue($actual);
    }

    #[Test]
    public function decide_sampleRateEquals0_returnsFalse(): void
    {
        // Given
        $sampleRate = 0.0;

        // When
        $actual = (new SampleRateSendDecider())->decide($sampleRate);

        // Then
        self::assertFalse($actual);
    }

    #[Test]
    public function decide_sampleRateBelow0_returnsFalse(): void
    {
        // Given
        $sampleRate = -110.0;

        // When
        $actual = (new SampleRateSendDecider())->decide($sampleRate);

        // Then
        self::assertFalse($actual);
    }
}
