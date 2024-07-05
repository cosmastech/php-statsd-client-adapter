<?php

namespace Cosmastech\StatsDClientAdapter\Tests\TagNormalizers;

use Cosmastech\StatsDClientAdapter\TagNormalizers\ConvertEnumNormalizer;
use Cosmastech\StatsDClientAdapter\Tests\TagNormalizers\Enums\IntBackedEnum;
use Cosmastech\StatsDClientAdapter\Tests\TagNormalizers\Enums\StringBackedEnum;
use Cosmastech\StatsDClientAdapter\Tests\TagNormalizers\Enums\UnbackedEnum;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class ConvertEnumNormalizerTest extends TestCase
{
    #[Test]
    public function normalize_convertsBackedEnumInValue()
    {
        // Given
        $convertEnumNormalizer = new ConvertEnumNormalizer();

        // And
        $arr = [
            "key-1" => StringBackedEnum::VALUE_1,
            "key-2" => IntBackedEnum::VALUE_IS_22,
            "key-3" => UnbackedEnum::NO_BACKING,
        ];

        // When
        $actual = $convertEnumNormalizer->normalize($arr);

        // Then
        self::assertEqualsCanonicalizing(
            ['key-1' => "my-first-value", "key-2" => 22, "key-3" => "NO_BACKING"],
            $actual
        );
    }
}
