<?php

declare(strict_types=1);

namespace SlashLab\Numerik\Tests\Fixtures;

use SlashLab\Numerik\Enums\ValidationFailureReason;

final class KrsFixtures
{
    /**
     * @return array<string, array{string}>
     */
    public static function valid(): array
    {
        return [
            'full 10 digits with leading zeros' => ['0000127206'],
            'short form without leading zeros'  => ['127206'],
            'minimum valid'                     => ['1'],
            'large number'                      => ['9999999998'],
        ];
    }

    /**
     * @return array<string, array{string, ValidationFailureReason}>
     */
    public static function invalid(): array
    {
        return [
            'all zeros'           => ['0000000000', ValidationFailureReason::AllZeros],
            'too long'            => ['00001272060', ValidationFailureReason::InvalidLength],
            'empty after strip'   => ['   ', ValidationFailureReason::InvalidLength],
            'invalid characters'  => ['KRS1234567', ValidationFailureReason::InvalidCharacters],
            'hyphens not allowed' => ['0001-27206', ValidationFailureReason::InvalidCharacters],
        ];
    }

    /**
     * @return array<string, array{string, ValidationFailureReason}>
     */
    public static function invalidStrict(): array
    {
        return [
            'all ones'  => ['1111111111', ValidationFailureReason::AllSameDigit],
            'all nines' => ['9999999999', ValidationFailureReason::AllSameDigit],
        ];
    }
}
