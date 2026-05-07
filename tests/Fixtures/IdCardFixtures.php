<?php

declare(strict_types=1);

namespace SlashLab\Numerik\Tests\Fixtures;

use SlashLab\Numerik\Enums\ValidationFailureReason;

final class IdCardFixtures
{
    /**
     * @return array<string, array{string}>
     */
    public static function valid(): array
    {
        return [
            'standard uppercase'   => ['ABC123454'],
            'different series'     => ['XYZ987659'],
            'zeros in number'      => ['ZBA000008'],
            'lowercase with hyphens' => ['abc-123-454'],
            'lowercase with spaces'  => ['abc 123 454'],
        ];
    }

    /**
     * @return array<string, array{string, ValidationFailureReason}>
     */
    public static function invalid(): array
    {
        return [
            'too short'              => ['ABC12345',   ValidationFailureReason::InvalidLength],
            'too long'               => ['ABC1234567', ValidationFailureReason::InvalidLength],
            'digit in series'        => ['1BC123456',  ValidationFailureReason::InvalidCharacters],
            'letter O in series'     => ['OBC123456',  ValidationFailureReason::InvalidFormat],
            'letter Q in series'     => ['QBC123456',  ValidationFailureReason::InvalidFormat],
            'letter in number'       => ['ABC12345A',  ValidationFailureReason::InvalidCharacters],
            'wrong checksum'         => ['ABC123453',  ValidationFailureReason::InvalidChecksum],
        ];
    }
}
