<?php

declare(strict_types=1);

namespace SlashLab\Numerik\Identifiers;

use DateTimeImmutable;
use SlashLab\Numerik\Contracts\ParserInterface;
use SlashLab\Numerik\Contracts\ValidatorInterface;
use SlashLab\Numerik\Enums\Gender;
use SlashLab\Numerik\Enums\ValidationFailureReason;
use SlashLab\Numerik\Exceptions\InvalidChecksumException;
use SlashLab\Numerik\Exceptions\InvalidDateException;
use SlashLab\Numerik\Exceptions\InvalidFormatException;
use SlashLab\Numerik\Result\ValidationResult;
use SlashLab\Numerik\ValueObjects\Pesel;

final class PeselIdentifier implements ValidatorInterface, ParserInterface
{
    private const array WEIGHTS    = [1, 3, 7, 9, 1, 3, 7, 9, 1, 3];
    private const int   MAX_LENGTH = 32;
    private const int   DIGITS     = 11;

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

        if (strlen($normalized) !== self::DIGITS) {
            return ValidationResult::failWithReason(
                ValidationFailureReason::InvalidLength,
                'PESEL must be exactly 11 digits.',
            );
        }

        if (! ctype_digit($normalized)) {
            return ValidationResult::failWithReason(
                ValidationFailureReason::InvalidCharacters,
                'PESEL must contain only digits.',
            );
        }

        $digits       = array_map('intval', str_split($normalized));
        $encodedMonth = $digits[2] * 10 + $digits[3];
        $month        = $this->decodeMonth($encodedMonth);

        if ($month === null) {
            return ValidationResult::failWithReason(
                ValidationFailureReason::InvalidMonth,
                'PESEL contains an invalid month encoding.',
            );
        }

        $year = $this->decodeYear($digits[0] * 10 + $digits[1], $encodedMonth);
        $day  = $digits[4] * 10 + $digits[5];

        if (! checkdate($month, $day, $year)) {
            return ValidationResult::failWithReason(
                ValidationFailureReason::InvalidDate,
                'PESEL contains an invalid date.',
            );
        }

        $sum = 0;
        for ($i = 0; $i < 10; $i++) {
            $sum += $digits[$i] * self::WEIGHTS[$i];
        }

        if ((10 - $sum % 10) % 10 !== $digits[10]) {
            return ValidationResult::failWithReason(
                ValidationFailureReason::InvalidChecksum,
                'PESEL checksum digit does not match.',
            );
        }

        if ($this->strict) {
            $birthDate = new DateTimeImmutable(sprintf('%04d-%02d-%02d', $year, $month, $day));

            if ($birthDate > new DateTimeImmutable('today')) {
                return ValidationResult::failWithReason(
                    ValidationFailureReason::FutureDate,
                    'PESEL birth date is in the future.',
                );
            }

            if (count(array_unique($digits)) === 1) {
                return ValidationResult::failWithReason(
                    ValidationFailureReason::AllSameDigit,
                    'PESEL consists of a single repeated digit.',
                );
            }
        }

        return ValidationResult::pass();
    }

    #[\Override]
    public function isValid(string $input): bool
    {
        return $this->validate($input)->isValid;
    }

    #[\Override]
    public function parse(string $input): Pesel
    {
        $result = $this->validate($input);

        foreach ($result->getFailures() as $failure) {
            throw match ($failure->reason) {
                ValidationFailureReason::InvalidChecksum => new InvalidChecksumException($failure->message),
                ValidationFailureReason::InvalidDate,
                ValidationFailureReason::FutureDate,
                ValidationFailureReason::InvalidMonth    => new InvalidDateException($failure->message),
                default                                  => new InvalidFormatException($failure->message),
            };
        }

        $normalized   = $this->normalize($input);
        $digits       = array_map('intval', str_split($normalized));
        $encodedMonth = $digits[2] * 10 + $digits[3];
        $month        = $this->decodeMonth($encodedMonth);

        assert($month !== null);

        $year          = $this->decodeYear($digits[0] * 10 + $digits[1], $encodedMonth);
        $day           = $digits[4] * 10 + $digits[5];
        $birthDate     = new DateTimeImmutable(sprintf('%04d-%02d-%02d', $year, $month, $day));
        $gender        = $digits[9] % 2 === 1 ? Gender::Male : Gender::Female;
        $ordinalNumber = $digits[6] * 1000 + $digits[7] * 100 + $digits[8] * 10 + $digits[9];

        return new Pesel(
            raw: $input,
            normalized: $normalized,
            birthDate: $birthDate,
            gender: $gender,
            ordinalNumber: $ordinalNumber,
        );
    }

    #[\Override]
    public function tryParse(string $input): ?Pesel
    {
        try {
            return $this->parse($input);
        } catch (InvalidFormatException|InvalidChecksumException|InvalidDateException) {
            return null;
        }
    }

    private function normalize(string $input): string
    {
        return str_replace(' ', '', $input);
    }

    private function decodeMonth(int $encodedMonth): ?int
    {
        return match (true) {
            $encodedMonth >= 1  && $encodedMonth <= 12 => $encodedMonth,
            $encodedMonth >= 21 && $encodedMonth <= 32 => $encodedMonth - 20,
            $encodedMonth >= 41 && $encodedMonth <= 52 => $encodedMonth - 40,
            $encodedMonth >= 61 && $encodedMonth <= 72 => $encodedMonth - 60,
            $encodedMonth >= 81 && $encodedMonth <= 92 => $encodedMonth - 80,
            default                                    => null,
        };
    }

    private function decodeYear(int $yy, int $encodedMonth): int
    {
        $century = match (true) {
            $encodedMonth >= 81 && $encodedMonth <= 92 => 1800,
            $encodedMonth >= 1  && $encodedMonth <= 12 => 1900,
            $encodedMonth >= 21 && $encodedMonth <= 32 => 2000,
            $encodedMonth >= 41 && $encodedMonth <= 52 => 2100,
            default                                    => 2200,
        };

        return $century + $yy;
    }
}
