<?php

declare(strict_types=1);

namespace SlashLab\Numerik\Tests\Fixtures;

use SlashLab\Numerik\Enums\ValidationFailureReason;

final class IbanFixtures
{
    /**
     * @return array<string, array{string}>
     */
    public static function valid(): array
    {
        return [
            'digits with prefix'   => ['PL61102010260000000000000000'],
            'lowercase prefix'     => ['pl61102010260000000000000000'],
            'with spaces'          => ['PL61 1020 1026 0000 0000 0000 0000'],
            'different sort code'  => ['PL19109020040000000000000000'],
            'non-zero account'     => ['PL54102010261234567890123456'],
        ];
    }

    /**
     * @return array<string, array{string, ValidationFailureReason}>
     */
    public static function invalid(): array
    {
        return [
            'missing prefix'      => ['61102010260000000000000000',   ValidationFailureReason::InvalidFormat],
            'wrong prefix'        => ['DE61102010260000000000000000', ValidationFailureReason::InvalidFormat],
            'wrong checksum'      => ['PL62102010260000000000000000', ValidationFailureReason::InvalidChecksum],
            'too short after PL'  => ['PL6110201026000000000000000',  ValidationFailureReason::InvalidLength],
            'too long after PL'   => ['PL611020102600000000000000001', ValidationFailureReason::InvalidLength],
            'invalid characters'  => ['PL61102010260000000000000ABC', ValidationFailureReason::InvalidCharacters],
        ];
    }
}
