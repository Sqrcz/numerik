<?php

declare(strict_types=1);

namespace SlashLab\Numerik\Tests\Fixtures;

use SlashLab\Numerik\Enums\ValidationFailureReason;

final class NipFixtures
{
    /**
     * @return array<string, array{string}>
     */
    public static function valid(): array
    {
        return [
            'digits only'   => ['5260250274'],
            'with hyphens'  => ['526-025-02-74'],
            'with spaces'   => ['526 025 02 74'],
            'another valid' => ['1002345672'],
        ];
    }

    /**
     * @return array<string, array{string, ValidationFailureReason}>
     */
    public static function invalid(): array
    {
        return [
            'tax office code 000'  => ['0001234567',  ValidationFailureReason::InvalidFormat],
            'wrong checksum'       => ['5260250275',  ValidationFailureReason::InvalidChecksum],
            'too short'            => ['526025027',   ValidationFailureReason::InvalidLength],
            'too long'             => ['52602502741', ValidationFailureReason::InvalidLength],
            'invalid characters'   => ['526ABC0274',  ValidationFailureReason::InvalidCharacters],
        ];
    }

    /**
     * @return array<string, array{string, ValidationFailureReason}>
     */
    public static function invalidStrict(): array
    {
        return [
            'all ones' => ['1111111111', ValidationFailureReason::AllSameDigit],
            'all nines' => ['9999999999', ValidationFailureReason::AllSameDigit],
        ];
    }
}
