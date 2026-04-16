<?php

declare(strict_types=1);

namespace SlashLab\Numerik\Tests\Fixtures;

use SlashLab\Numerik\Enums\ValidationFailureReason;

final class PeselFixtures
{
    /**
     * @return array<string, array{string}>
     */
    public static function valid(): array
    {
        return [
            '1900s male'    => ['44051401458'],  // 1944-05-14, male
            '1900s female'  => ['90123112340'],  // 1990-12-31, female
            '2000s male'    => ['02210213452'],  // 2002-01-02, male
            '1800s female'  => ['98831512348'],  // 1898-03-15, female
            'with spaces'   => ['44051 401458'], // same as 1900s male with space
        ];
    }

    /**
     * @return array<string, array{string, ValidationFailureReason}>
     */
    public static function invalid(): array
    {
        return [
            'wrong checksum'    => ['44051401459', ValidationFailureReason::InvalidChecksum],
            'too short'         => ['4405140145',  ValidationFailureReason::InvalidLength],
            'too long'          => ['444051401458', ValidationFailureReason::InvalidLength],
            'invalid chars'     => ['4405140145A',  ValidationFailureReason::InvalidCharacters],
            'invalid month 00'  => ['44001401453',  ValidationFailureReason::InvalidMonth],
            'invalid month 13'  => ['44131401453',  ValidationFailureReason::InvalidMonth],
            'invalid date'      => ['44023101452',  ValidationFailureReason::InvalidDate],   // Feb 31
            'all same digit'    => ['22222222222',  ValidationFailureReason::AllSameDigit],
        ];
    }
}
