<?php

declare(strict_types=1);

namespace SlashLab\Numerik\Tests\Fixtures;

use SlashLab\Numerik\Enums\ValidationFailureReason;

final class RegonFixtures
{
    /**
     * @return array<string, array{string}>
     */
    public static function valid(): array
    {
        return [
            '9-digit'                    => ['850518457'],
            '9-digit public record'      => ['000331501'],
            '9-digit with spaces'        => ['850 518 457'],
            '9-digit diverse weights'    => ['123456785'],     // sum=192, 192%11=5
            '9-digit mod11 edge case'    => ['000000030'],     // sum=21, 21%11=10 → checksum=0
            '14-digit'                   => ['85051845749370'],
            '14-digit mod11 edge case'   => ['00000003010000'], // 14-digit where suffix checksum mod11=10→0
            '14-digit non-zero checksum' => ['85051845770005'], // 14-digit where suffix checksum=5 (non-zero)
        ];
    }

    /**
     * @return array<string, array{string, ValidationFailureReason}>
     */
    public static function invalid(): array
    {
        return [
            'wrong 9-digit checksum'         => ['850518456',      ValidationFailureReason::InvalidChecksum],
            'wrong 14-digit base checksum'   => ['85051845849370', ValidationFailureReason::InvalidChecksum],
            'wrong 14-digit suffix checksum' => ['85051845749371', ValidationFailureReason::InvalidChecksum],
            'too short'                      => ['85051845',       ValidationFailureReason::InvalidLength],
            'invalid length (10 digits)'     => ['8505184574',     ValidationFailureReason::InvalidLength],
            'invalid characters'             => ['85051845A',      ValidationFailureReason::InvalidCharacters],
        ];
    }
}
