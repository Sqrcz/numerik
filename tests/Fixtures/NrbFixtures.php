<?php

declare(strict_types=1);

namespace SlashLab\Numerik\Tests\Fixtures;

use SlashLab\Numerik\Enums\ValidationFailureReason;

final class NrbFixtures
{
    /**
     * @return array<string, array{string}>
     */
    public static function valid(): array
    {
        return [
            'digits only'          => ['61102010260000000000000000'],
            'with spaces'          => ['61 1020 1026 0000 0000 0000 0000'],
            'IBAN format'          => ['PL61102010260000000000000000'],
            'IBAN with spaces'     => ['PL61 1020 1026 0000 0000 0000 0000'],
            'different sort code'  => ['19109020040000000000000000'],
            'non-zero account'     => ['54102010261234567890123456'],
        ];
    }

    /**
     * @return array<string, array{string, ValidationFailureReason}>
     */
    public static function invalid(): array
    {
        return [
            'wrong checksum'      => ['62102010260000000000000000', ValidationFailureReason::InvalidChecksum],
            'too short'           => ['6110201026000000000000000',  ValidationFailureReason::InvalidLength],
            'too long'            => ['611020102600000000000000001', ValidationFailureReason::InvalidLength],
            'invalid characters'  => ['61102010260000000000000ABC',  ValidationFailureReason::InvalidCharacters],
        ];
    }
}
