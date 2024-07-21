<?php

namespace Cosmastech\StatsDClientAdapter\Tests\DataProviders;

use Cosmastech\StatsDClientAdapter\Tests\Enums\IntBackedEnum;
use Cosmastech\StatsDClientAdapter\Tests\Enums\PlainUnitEnum;
use Cosmastech\StatsDClientAdapter\Tests\Enums\StringBackedEnum;
use UnitEnum;

class EnumProvider
{
    /**
     * @return array<string, array{0: UnitEnum, 1: string}>
     */
    public static function differentEnumTypesAndExpectedStringDataProvider(): array
    {
        return [
            "unit enum" => [PlainUnitEnum::FIRST, 'FIRST'],
            "string-backed enum" => [StringBackedEnum::A, 'a'],
            "int-backed enum" => [IntBackedEnum::ONE, '1'],
        ];
    }
}
