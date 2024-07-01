<?php

namespace Cosmastech\StatsDClient\Tests\TagNormalizers;

use Cosmastech\StatsDClient\TagNormalizers\ConvertEnumNormalizer;
use Cosmastech\StatsDClient\Tests\TagNormalizers\Enums\IntBackedEnum;
use Cosmastech\StatsDClient\Tests\TagNormalizers\Enums\StringBackedEnum;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class ConvertEnumNormalizerTest extends TestCase
{
    #[Test]
    public function normalize_convertsBackedEnumInValue() {
        // Given
        $convertEnumNormalizer = new ConvertEnumNormalizer();

        // And
        $arr = [
            "key-1" => StringBackedEnum::VALUE_1,
            "key-2" => IntBackedEnum::VALUE_IS_22,
        ];

        // When
        $actual = $convertEnumNormalizer->normalize($arr);

        // Then
        self::assertEqualsCanonicalizing(['key-1' => "my-first-value", "key-2" => 22], $actual);
    }
}
