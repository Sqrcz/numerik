<?php

declare(strict_types=1);

namespace SlashLab\Numerik\Result;

use SlashLab\Numerik\Enums\ValidationFailureReason;
use SlashLab\Numerik\Exceptions\InvalidChecksumException;
use SlashLab\Numerik\Exceptions\InvalidDateException;
use SlashLab\Numerik\Exceptions\InvalidFormatException;
use SlashLab\Numerik\Exceptions\ValidationException;

final readonly class ValidationFailure
{
    public function __construct(
        public ValidationFailureReason $reason,
        public string $message,
    ) {
    }

    public function toException(): ValidationException
    {
        return match ($this->reason) {
            ValidationFailureReason::InvalidChecksum => new InvalidChecksumException($this->message),
            ValidationFailureReason::InvalidDate,
            ValidationFailureReason::FutureDate,
            ValidationFailureReason::InvalidMonth    => new InvalidDateException($this->message),
            default                                  => new InvalidFormatException($this->message),
        };
    }
}
