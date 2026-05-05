<?php

declare(strict_types=1);

namespace SlashLab\Numerik\Identifiers;

use SlashLab\Numerik\Contracts\ParserInterface;
use SlashLab\Numerik\Contracts\ValidatorInterface;
use SlashLab\Numerik\Enums\ValidationFailureReason;
use SlashLab\Numerik\Exceptions\InvalidChecksumException;
use SlashLab\Numerik\Exceptions\InvalidFormatException;
use SlashLab\Numerik\Result\ValidationResult;
use SlashLab\Numerik\ValueObjects\VatEu;

final class VatEuIdentifier implements ValidatorInterface, ParserInterface
{
    private const int    RAW_MAX_LENGTH = 32;
    private const int    NIP_DIGITS     = 10;
    private const string PREFIX         = 'PL';

    private NipIdentifier $nip;

    public function __construct(
        private readonly bool $strict = true,
    ) {
        $this->nip = new NipIdentifier(strict: $strict);
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
                'Input exceeds maximum length of 32 characters.',
            );
        }

        $stripped = $this->stripSeparators($input);

        if (strlen($stripped) < 2 || strtoupper(substr($stripped, 0, 2)) !== self::PREFIX) {
            return ValidationResult::failWithReason(
                ValidationFailureReason::InvalidFormat,
                'VAT-EU number must start with the PL country prefix.',
            );
        }

        $nipPart = substr($stripped, 2);

        if (strlen($nipPart) !== self::NIP_DIGITS) {
            return ValidationResult::failWithReason(
                ValidationFailureReason::InvalidLength,
                'VAT-EU must contain exactly 10 digits after the PL prefix.',
            );
        }

        return $this->nip->validate($nipPart);
    }

    #[\Override]
    public function isValid(string $input): bool
    {
        return $this->validate($input)->isValid;
    }

    #[\Override]
    public function parse(string $input): VatEu
    {
        $result = $this->validate($input);

        foreach ($result->getFailures() as $failure) {
            throw match ($failure->reason) {
                ValidationFailureReason::InvalidChecksum => new InvalidChecksumException($failure->message),
                default                                  => new InvalidFormatException($failure->message),
            };
        }

        return new VatEu(
            raw: $input,
            normalized: $this->normalize($input),
        );
    }

    #[\Override]
    public function tryParse(string $input): ?VatEu
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
