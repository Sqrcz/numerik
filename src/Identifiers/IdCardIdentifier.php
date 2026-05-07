<?php

declare(strict_types=1);

namespace SlashLab\Numerik\Identifiers;

use SlashLab\Numerik\Contracts\ParserInterface;
use SlashLab\Numerik\Contracts\ValidatorInterface;
use SlashLab\Numerik\Enums\ValidationFailureReason;
use SlashLab\Numerik\Exceptions\InvalidChecksumException;
use SlashLab\Numerik\Exceptions\InvalidFormatException;
use SlashLab\Numerik\Result\ValidationResult;
use SlashLab\Numerik\ValueObjects\IdCard;

final class IdCardIdentifier implements ValidatorInterface, ParserInterface
{
    private const array WEIGHTS    = [7, 3, 1, 7, 3, 1, 7, 3];
    private const int   MAX_LENGTH = 32;
    private const int   LENGTH     = 9;

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

        if (strlen($normalized) !== self::LENGTH) {
            return ValidationResult::failWithReason(
                ValidationFailureReason::InvalidLength,
                'Identity card number must be exactly 9 characters.',
            );
        }

        $series = substr($normalized, 0, 3);

        if (! ctype_alpha($series)) {
            return ValidationResult::failWithReason(
                ValidationFailureReason::InvalidCharacters,
                'Identity card series (first 3 characters) must contain only letters.',
            );
        }

        if (str_contains($series, 'O') || str_contains($series, 'Q')) {
            return ValidationResult::failWithReason(
                ValidationFailureReason::InvalidFormat,
                'Identity card series cannot contain the letters O or Q.',
            );
        }

        if (! ctype_digit(substr($normalized, 3))) {
            return ValidationResult::failWithReason(
                ValidationFailureReason::InvalidCharacters,
                'Identity card number portion (characters 4–9) must contain only digits.',
            );
        }

        if (! $this->isValidChecksum($normalized)) {
            return ValidationResult::failWithReason(
                ValidationFailureReason::InvalidChecksum,
                'Identity card checksum digit does not match.',
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
    public function parse(string $input): IdCard
    {
        $result = $this->validate($input);

        foreach ($result->getFailures() as $failure) {
            throw match ($failure->reason) {
                ValidationFailureReason::InvalidChecksum => new InvalidChecksumException($failure->message),
                default => new InvalidFormatException($failure->message),
            };
        }

        return new IdCard(
            raw: $input,
            normalized: $this->normalize($input),
        );
    }

    #[\Override]
    public function tryParse(string $input): ?IdCard
    {
        try {
            return $this->parse($input);
        } catch (InvalidFormatException|InvalidChecksumException) {
            return null;
        }
    }

    private function normalize(string $input): string
    {
        return strtoupper(str_replace(['-', ' '], '', $input));
    }

    private function isValidChecksum(string $normalized): bool
    {
        $chars = str_split($normalized);
        $sum   = 0;

        for ($i = 0; $i < 8; $i++) {
            $sum += $this->icaoCharValue($chars[$i]) * self::WEIGHTS[$i];
        }

        return ($sum % 10) === (int) $chars[8];
    }

    private function icaoCharValue(string $char): int
    {
        return ctype_digit($char) ? (int) $char : (ord($char) - 55);
    }
}
