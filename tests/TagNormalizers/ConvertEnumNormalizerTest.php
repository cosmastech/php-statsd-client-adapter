<?php

namespace Cosmastech\StatsDClientAdapter\Tests\TagNormalizers;

use Cosmastech\StatsDClientAdapter\TagNormalizers\ConvertEnumNormalizer;
use Cosmastech\StatsDClientAdapter\Tests\Enums\IntBackedEnum;
use Cosmastech\StatsDClientAdapter\Tests\Enums\PlainUnitEnum;
use Cosmastech\StatsDClientAdapter\Tests\Enums\StringBackedEnum;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(ConvertEnumNormalizer::class)]
class ConvertEnumNormalizerTest extends TestCase
{
    #[Test]
    public function normalize_convertsBackedEnumInValue(): void
    {
        // Given
        $convertEnumNormalizer = new ConvertEnumNormalizer();

        // And
        $arr = [
            "key-1" => StringBackedEnum::A,
            "key-2" => IntBackedEnum::TWO,
            "key-3" => PlainUnitEnum::SECOND,
        ];

        // When
        $actual = $convertEnumNormalizer->normalize($arr);

        // Then
        self::assertEqualsCanonicalizing(
            ['key-1' => 'a', 'key-2' => 2, 'key-3' => 'SECOND'],
            $actual
        );
    }
}
