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
            '9-digit'               => ['850518457'],
            '9-digit public record' => ['000331501'],
            '9-digit with spaces'   => ['850 518 457'],
            '14-digit'              => ['85051845749370'],
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
