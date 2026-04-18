<?php

declare(strict_types=1);

namespace SlashLab\Numerik\Identifiers;

use SlashLab\Numerik\Contracts\ParserInterface;
use SlashLab\Numerik\Contracts\ValidatorInterface;
use SlashLab\Numerik\Enums\RegonType;
use SlashLab\Numerik\Enums\ValidationFailureReason;
use SlashLab\Numerik\Exceptions\InvalidChecksumException;
use SlashLab\Numerik\Exceptions\InvalidFormatException;
use SlashLab\Numerik\Result\ValidationResult;
use SlashLab\Numerik\ValueObjects\Regon;

final class RegonIdentifier implements ValidatorInterface, ParserInterface
{
    private const array WEIGHTS_9  = [8, 9, 2, 3, 4, 5, 6, 7];
    private const array WEIGHTS_14 = [2, 4, 8, 5, 0, 9, 7, 3, 6, 1, 2, 4, 8];
    private const int   MAX_LENGTH = 32;
    private const int   DIGITS_9   = 9;
    private const int   DIGITS_14  = 14;

    public function __construct(
        private readonly bool $strict = true,
    ) {
    }

    public function isStrict(): bool
    {
        return $this->strict;
    }

    #[\Override]
    public function validate(string $input): ValidationResult
    {
        if (strlen($input) > self::MAX_LENGTH) {
            return ValidationResult::failWithReason(
                ValidationFailureReason::InvalidLength,
                'Input exceeds maximum length of 32 characters.',
            );
        }

        $normalized = $this->normalize($input);
        $length     = strlen($normalized);

        if ($length !== self::DIGITS_9 && $length !== self::DIGITS_14) {
            return ValidationResult::failWithReason(
                ValidationFailureReason::InvalidLength,
                'REGON must be exactly 9 or 14 digits.',
            );
        }

        if (! ctype_digit($normalized)) {
            return ValidationResult::failWithReason(
                ValidationFailureReason::InvalidCharacters,
                'REGON must contain only digits and spaces.',
            );
        }

        $digits = array_map('intval', str_split($normalized));

        if (! $this->isValid9DigitChecksum($digits)) {
            return ValidationResult::failWithReason(
                ValidationFailureReason::InvalidChecksum,
                'REGON checksum digit does not match.',
            );
        }

        if ($length === self::DIGITS_14 && ! $this->isValid14DigitChecksum($digits)) {
            return ValidationResult::failWithReason(
                ValidationFailureReason::InvalidChecksum,
                'REGON local unit checksum digit does not match.',
            );
        }

        return ValidationResult::pass();
    }

    #[\Override]
    public function isValid(string $input): bool
    {
        return $this->validate($input)->isValid;
    }

    #[\Override]
    public function parse(string $input): Regon
    {
        $result = $this->validate($input);

        foreach ($result->getFailures() as $failure) {
            throw match ($failure->reason) {
                ValidationFailureReason::InvalidChecksum => new InvalidChecksumException($failure->message),
                default => new InvalidFormatException($failure->message),
            };
        }

        $normalized = $this->normalize($input);
        $type       = strlen($normalized) === self::DIGITS_9
            ? RegonType::Individual
            : RegonType::LegalEntity;

        return new Regon(
            raw: $input,
            normalized: $normalized,
            type: $type,
        );
    }

    #[\Override]
    public function tryParse(string $input): ?Regon
    {
        try {
            return $this->parse($input);
        } catch (InvalidFormatException|InvalidChecksumException) {
            return null;
        }
    }

    private function normalize(string $input): string
    {
        return str_replace(' ', '', $input);
    }

    /** @param list<int> $digits */
    private function isValid9DigitChecksum(array $digits): bool
    {
        $sum = 0;
        for ($i = 0; $i < 8; $i++) {
            $sum += $digits[$i] * self::WEIGHTS_9[$i];
        }

        $checksum = $sum % 11;

        return ($checksum === 10 ? 0 : $checksum) === $digits[8];
    }

    /** @param list<int> $digits */
    private function isValid14DigitChecksum(array $digits): bool
    {
        $sum = 0;
        for ($i = 0; $i < 13; $i++) {
            $sum += $digits[$i] * self::WEIGHTS_14[$i];
        }

        $checksum = $sum % 11;

        return ($checksum === 10 ? 0 : $checksum) === $digits[13];
    }
}
