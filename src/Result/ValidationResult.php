<?php

declare(strict_types=1);

namespace SlashLab\Numerik\Result;

use SlashLab\Numerik\Enums\ValidationFailureReason;

final readonly class ValidationResult
{
    /**
     * @param list<ValidationFailure> $failures
     */
    public function __construct(
        public bool $isValid,
        public array $failures = [],
    ) {
    }

    public static function pass(): self
    {
        return new self(isValid: true);
    }

    /**
     * @param list<ValidationFailure> $failures
     */
    public static function fail(array $failures): self
    {
        return new self(isValid: false, failures: $failures);
    }

    public static function failWithReason(
        ValidationFailureReason $reason,
        string $message,
    ): self {
        return new self(
            isValid: false,
            failures: [new ValidationFailure($reason, $message)],
        );
    }

    public function isFailed(): bool
    {
        return ! $this->isValid;
    }

    public function getFailures(): array
    {
        return $this->failures;
    }

    public function getFirstFailure(): ?ValidationFailure
    {
        return $this->failures[0] ?? null;
    }

    public function hasFailureReason(ValidationFailureReason $reason): bool
    {
        foreach ($this->failures as $failure) {
            if ($failure->reason === $reason) {
                return true;
            }
        }

        return false;
    }
}
