<?php

declare(strict_types=1);

namespace SlashLab\Numerik\Identifiers;

use SlashLab\Numerik\Contracts\ParserInterface;
use SlashLab\Numerik\Contracts\ValidatorInterface;
use SlashLab\Numerik\Enums\ValidationFailureReason;
use SlashLab\Numerik\Exceptions\InvalidFormatException;
use SlashLab\Numerik\Result\ValidationResult;
use SlashLab\Numerik\ValueObjects\Krs;

final class KrsIdentifier implements ValidatorInterface, ParserInterface
{
    private const MAX_LENGTH = 32;
    private const MAX_DIGITS = 10;

    public function __construct(
        private readonly bool $strict = true,
    ) {
    }

    public function isStrict(): bool
    {
        return $this->strict;
    }

    public function validate(string $input): ValidationResult
    {
        if (strlen($input) > self::MAX_LENGTH) {
            return ValidationResult::failWithReason(
                ValidationFailureReason::InvalidLength,
                'Input exceeds maximum length of 32 characters.',
            );
        }

        $normalized = $this->normalize($input);

        if (strlen($normalized) === 0 || strlen($normalized) > self::MAX_DIGITS) {
            return ValidationResult::failWithReason(
                ValidationFailureReason::InvalidLength,
                'KRS must have between 1 and 10 digits.',
            );
        }

        if (! ctype_digit($normalized)) {
            return ValidationResult::failWithReason(
                ValidationFailureReason::InvalidCharacters,
                'KRS must contain only digits and spaces.',
            );
        }

        if ((int) $normalized === 0) {
            return ValidationResult::failWithReason(
                ValidationFailureReason::AllZeros,
                'KRS cannot be all zeros.',
            );
        }

        $padded = str_pad($normalized, self::MAX_DIGITS, '0', STR_PAD_LEFT);

        if ($this->strict && count(array_unique(str_split($padded))) === 1) {
            return ValidationResult::failWithReason(
                ValidationFailureReason::AllSameDigit,
                'KRS consists of a single repeated digit.',
            );
        }

        return ValidationResult::pass();
    }

    public function isValid(string $input): bool
    {
        return $this->validate($input)->isValid;
    }

    public function parse(string $input): Krs
    {
        $result = $this->validate($input);

        foreach ($result->getFailures() as $failure) {
            throw new InvalidFormatException($failure->message);
        }

        return new Krs(
            raw: $input,
            normalized: $this->normalize($input),
        );
    }

    public function tryParse(string $input): ?Krs
    {
        try {
            return $this->parse($input);
        } catch (InvalidFormatException) {
            return null;
        }
    }

    private function normalize(string $input): string
    {
        return str_replace(' ', '', $input);
    }
}
