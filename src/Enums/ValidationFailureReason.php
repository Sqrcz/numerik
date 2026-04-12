<?php

declare(strict_types=1);

namespace SlashLab\Numerik\Enums;

enum ValidationFailureReason: string
{
    // Format
    case InvalidLength = 'invalid_length';
    case InvalidCharacters = 'invalid_characters';
    case InvalidFormat = 'invalid_format';

    // Checksum
    case InvalidChecksum = 'invalid_checksum';

    // Encoded data
    case InvalidDate = 'invalid_date';
    case FutureDate = 'future_date';
    case InvalidMonth = 'invalid_month';

    // Semantic
    case AllZeros = 'all_zeros';
    case AllSameDigit = 'all_same_digit';

    public function label(): string
    {
        return match($this) {
            ValidationFailureReason::InvalidLength => 'Invalid length',
            ValidationFailureReason::InvalidCharacters => 'Invalid characters',
            ValidationFailureReason::InvalidFormat => 'Invalid format',

            ValidationFailureReason::InvalidChecksum => 'Invalid checksum',

            ValidationFailureReason::InvalidDate => 'Invalid date',
            ValidationFailureReason::FutureDate => 'Future date',
            ValidationFailureReason::InvalidMonth => 'Invalid month',

            ValidationFailureReason::AllZeros => 'All zeros',
            ValidationFailureReason::AllSameDigit => 'All same digit',
        };
    }
}
