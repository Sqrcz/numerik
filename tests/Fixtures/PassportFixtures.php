<?php

declare(strict_types=1);

namespace SlashLab\Numerik\Tests\Fixtures;

use SlashLab\Numerik\Enums\ValidationFailureReason;

final class PassportFixtures
{
    /**
     * @return array<string, array{string}>
     */
    public static function valid(): array
    {
        return [
            'standard uppercase'     => ['AB1234564'],
            'all same series letter' => ['ZZ1234561'],
            'zeros in number'        => ['AA0000000'],
            'lowercase with spaces'  => ['ab 123456 4'],
            'uppercase with hyphens' => ['AB-1234564'],
        ];
    }

    /**
     * @return array<string, array{string, ValidationFailureReason}>
     */
    public static function invalid(): array
    {
        return [
            'too short'        => ['AB123456',   ValidationFailureReason::InvalidLength],
            'too long'         => ['AB12345678', ValidationFailureReason::InvalidLength],
            'digit in series'  => ['1B1234564',  ValidationFailureReason::InvalidCharacters],
            'letter in number' => ['AB123456A',  ValidationFailureReason::InvalidCharacters],
            'wrong checksum'   => ['AB1234563',  ValidationFailureReason::InvalidChecksum],
        ];
    }
}
