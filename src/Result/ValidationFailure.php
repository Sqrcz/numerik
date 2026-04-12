<?php

declare(strict_types=1);

namespace SlashLab\Numerik\Result;

use SlashLab\Numerik\Enums\ValidationFailureReason;

final readonly class ValidationFailure
{
    public function __construct(
        public ValidationFailureReason $reason,
        public string $message,
    ) {
    }
}
