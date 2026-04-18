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
            '1900s male'         => ['44051401458'],  // 1944-05-14, male
            '1900s female'       => ['90123112340'],  // 1990-12-31, female
            '1900s month 12'     => ['44121401458'],  // 1944-12-14, male — boundary month 12
            '2000s male'         => ['02210213452'],  // 2002-01-02, male — boundary month 21
            '2000s month 32'     => ['05322001227'],  // 2005-12-20, female — boundary month 32
            '1900s month 1'      => ['44011401454'],  // 1944-01-14, male — boundary month 1
            '1800s female'       => ['98831512348'],  // 1898-03-15, female
            '1800s month 81'     => ['98811501236'],  // 1898-01-15, male — boundary month 81
            '1800s month 92'     => ['98922001241'],  // 1898-12-20, female — boundary month 92
            '1996 leap day'      => ['96022901236'],  // 1996-02-29, male
            'with spaces'        => ['44051 401458'], // same as 1900s male with space
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
