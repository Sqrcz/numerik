<?php

declare(strict_types=1);

namespace SlashLab\Numerik\Tests\Fixtures;

use SlashLab\Numerik\Enums\ValidationFailureReason;

final class VatEuFixtures
{
    /**
     * @return array<string, array{string}>
     */
    public static function valid(): array
    {
        return [
            'digits only'        => ['PL5260250274'],
            'lowercase prefix'   => ['pl5260250274'],
            'with hyphens'       => ['PL526-025-02-74'],
            'with spaces'        => ['PL526 025 02 74'],
            'another valid'      => ['PL1002345672'],
        ];
    }

    /**
     * @return array<string, array{string, ValidationFailureReason}>
     */
    public static function invalid(): array
    {
        return [
            'missing prefix'      => ['5260250274',     ValidationFailureReason::InvalidFormat],
            'wrong prefix'        => ['DE5260250274',   ValidationFailureReason::InvalidFormat],
            'wrong checksum'      => ['PL5260250275',   ValidationFailureReason::InvalidChecksum],
            'too short after PL'  => ['PL526025027',    ValidationFailureReason::InvalidLength],
            'too long after PL'   => ['PL52602502741',  ValidationFailureReason::InvalidLength],
            'invalid characters'  => ['PLABC0250274',   ValidationFailureReason::InvalidCharacters],
            'tax office 000'      => ['PL0001234567',   ValidationFailureReason::InvalidFormat],
        ];
    }

    /**
     * @return array<string, array{string, ValidationFailureReason}>
     */
    public static function invalidStrict(): array
    {
        return [
            'all ones' => ['PL1111111111', ValidationFailureReason::AllSameDigit],
        ];
    }
}
