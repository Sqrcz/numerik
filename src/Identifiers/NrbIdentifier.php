<?php

declare(strict_types=1);

namespace SlashLab\Numerik\Identifiers;

use SlashLab\Numerik\Contracts\ParserInterface;
use SlashLab\Numerik\Contracts\ValidatorInterface;
use SlashLab\Numerik\Enums\ValidationFailureReason;
use SlashLab\Numerik\Exceptions\InvalidChecksumException;
use SlashLab\Numerik\Exceptions\InvalidFormatException;
use SlashLab\Numerik\Result\ValidationResult;
use SlashLab\Numerik\ValueObjects\Nrb;

final class NrbIdentifier implements ValidatorInterface, ParserInterface
{
    private const int DIGITS     = 26;
    private const int MAX_LENGTH = 40;

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
                'Input exceeds maximum length of 40 characters.',
            );
        }

        $normalized = $this->normalize($input);

        if (strlen($normalized) !== self::DIGITS) {
            return ValidationResult::failWithReason(
                ValidationFailureReason::InvalidLength,
                'NRB must be exactly 26 digits.',
            );
        }

        if (! ctype_digit($normalized)) {
            return ValidationResult::failWithReason(
                ValidationFailureReason::InvalidCharacters,
                'NRB must contain only digits.',
            );
        }

        if (! $this->isValidChecksum($normalized)) {
            return ValidationResult::failWithReason(
                ValidationFailureReason::InvalidChecksum,
                'NRB checksum (MOD-97) does not match.',
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
    public function parse(string $input): Nrb
    {
        $result = $this->validate($input);

        foreach ($result->getFailures() as $failure) {
            throw match ($failure->reason) {
                ValidationFailureReason::InvalidChecksum => new InvalidChecksumException($failure->message),
                default                                  => new InvalidFormatException($failure->message),
            };
        }

        return new Nrb(
            raw: $input,
            normalized: $this->normalize($input),
        );
    }

    #[\Override]
    public function tryParse(string $input): ?Nrb
    {
        try {
            return $this->parse($input);
        } catch (InvalidFormatException | InvalidChecksumException) {
            return null;
        }
    }

    private function normalize(string $input): string
    {
        $stripped = str_replace([' ', '-'], '', $input);

        if (str_starts_with(strtoupper($stripped), 'PL')) {
            $stripped = substr($stripped, 2);
        }

        return $stripped;
    }

    private function isValidChecksum(string $normalized): bool
    {
        $rearranged = substr($normalized, 2) . '2521' . substr($normalized, 0, 2);
        $remainder  = 0;

        foreach (str_split($rearranged) as $digit) {
            $remainder = ($remainder * 10 + (int) $digit) % 97;
        }

        return $remainder === 1;
    }
}
