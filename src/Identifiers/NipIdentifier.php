<?php

declare(strict_types=1);

namespace SlashLab\Numerik\Identifiers;

use SlashLab\Numerik\Contracts\ParserInterface;
use SlashLab\Numerik\Contracts\ValidatorInterface;
use SlashLab\Numerik\Enums\ValidationFailureReason;
use SlashLab\Numerik\Exceptions\InvalidChecksumException;
use SlashLab\Numerik\Exceptions\InvalidFormatException;
use SlashLab\Numerik\Result\ValidationResult;
use SlashLab\Numerik\ValueObjects\Nip;

final class NipIdentifier implements ValidatorInterface, ParserInterface
{
    private const WEIGHTS = [6, 5, 7, 2, 3, 4, 5, 6, 7];
    private const MAX_LENGTH = 32;
    private const DIGITS = 10;

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

        if (strlen($normalized) !== self::DIGITS) {
            return ValidationResult::failWithReason(
                ValidationFailureReason::InvalidLength,
                'NIP must be exactly 10 digits.',
            );
        }

        if (! ctype_digit($normalized)) {
            return ValidationResult::failWithReason(
                ValidationFailureReason::InvalidCharacters,
                'NIP must contain only digits, hyphens, and spaces.',
            );
        }

        if (str_starts_with($normalized, '000')) {
            return ValidationResult::failWithReason(
                ValidationFailureReason::InvalidFormat,
                'NIP tax office code cannot be 000.',
            );
        }

        $digits = array_map('intval', str_split($normalized));

        $sum = 0;
        for ($i = 0; $i < 9; $i++) {
            $sum += $digits[$i] * self::WEIGHTS[$i];
        }

        if ($sum % 11 !== $digits[9]) {
            return ValidationResult::failWithReason(
                ValidationFailureReason::InvalidChecksum,
                'NIP checksum digit does not match.',
            );
        }

        if ($this->strict && count(array_unique($digits)) === 1) {
            return ValidationResult::failWithReason(
                ValidationFailureReason::AllSameDigit,
                'NIP consists of a single repeated digit.',
            );
        }

        return ValidationResult::pass();
    }

    public function isValid(string $input): bool
    {
        return $this->validate($input)->isValid;
    }

    public function parse(string $input): Nip
    {
        $result = $this->validate($input);

        foreach ($result->getFailures() as $failure) {
            throw match ($failure->reason) {
                ValidationFailureReason::InvalidChecksum => new InvalidChecksumException($failure->message),
                default => new InvalidFormatException($failure->message),
            };
        }

        return new Nip(
            raw: $input,
            normalized: $this->normalize($input),
        );
    }

    public function tryParse(string $input): ?Nip
    {
        try {
            return $this->parse($input);
        } catch (InvalidFormatException|InvalidChecksumException) {
            return null;
        }
    }

    private function normalize(string $input): string
    {
        return str_replace(['-', ' '], '', $input);
    }
}
