<?php

declare(strict_types=1);

namespace SlashLab\Numerik\Identifiers;

use SlashLab\Numerik\Contracts\ParserInterface;
use SlashLab\Numerik\Contracts\ValidatorInterface;
use SlashLab\Numerik\Enums\ValidationFailureReason;
use SlashLab\Numerik\Exceptions\InvalidChecksumException;
use SlashLab\Numerik\Exceptions\InvalidFormatException;
use SlashLab\Numerik\Result\ValidationResult;
use SlashLab\Numerik\ValueObjects\Iban;

final class IbanIdentifier implements ValidatorInterface, ParserInterface
{
    private const int    RAW_MAX_LENGTH = 40;
    private const int    NRB_DIGITS     = 26;
    private const string PREFIX         = 'PL';

    private NrbIdentifier $nrb;

    public function __construct(
        private readonly bool $strict = true,
    ) {
        $this->nrb = new NrbIdentifier(strict: $strict);
    }

    public function isStrict(): bool
    {
        return $this->strict;
    }

    #[\Override]
    public function validate(string $input): ValidationResult
    {
        if (strlen($input) > self::RAW_MAX_LENGTH) {
            return ValidationResult::failWithReason(
                ValidationFailureReason::InvalidLength,
                'Input exceeds maximum length of 40 characters.',
            );
        }

        $stripped = $this->stripSeparators($input);

        if (strlen($stripped) < 2 || strtoupper(substr($stripped, 0, 2)) !== self::PREFIX) {
            return ValidationResult::failWithReason(
                ValidationFailureReason::InvalidFormat,
                'IBAN must start with the PL country prefix.',
            );
        }

        $nrbPart = substr($stripped, 2);

        if (strlen($nrbPart) !== self::NRB_DIGITS) {
            return ValidationResult::failWithReason(
                ValidationFailureReason::InvalidLength,
                'IBAN must contain exactly 26 digits after the PL prefix.',
            );
        }

        return $this->nrb->validate($nrbPart);
    }

    #[\Override]
    public function isValid(string $input): bool
    {
        return $this->validate($input)->isValid;
    }

    #[\Override]
    public function parse(string $input): Iban
    {
        $result = $this->validate($input);

        foreach ($result->getFailures() as $failure) {
            throw $failure->toException();
        }

        return new Iban(
            raw: $input,
            normalized: $this->normalize($input),
        );
    }

    #[\Override]
    public function tryParse(string $input): ?Iban
    {
        try {
            return $this->parse($input);
        } catch (InvalidFormatException | InvalidChecksumException) {
            return null;
        }
    }

    private function stripSeparators(string $input): string
    {
        return str_replace([' ', '-'], '', $input);
    }

    private function normalize(string $input): string
    {
        $stripped = $this->stripSeparators($input);

        return self::PREFIX . substr($stripped, 2);
    }
}
