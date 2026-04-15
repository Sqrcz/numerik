<?php

declare(strict_types=1);

namespace SlashLab\Numerik\Identifiers;

use SlashLab\Numerik\Contracts\ParserInterface;
use SlashLab\Numerik\Contracts\ValidatorInterface;
use SlashLab\Numerik\Exceptions\ValidationException;
use SlashLab\Numerik\Result\ValidationResult;
use SlashLab\Numerik\ValueObjects\Krs;

final class KrsIdentifier implements ValidatorInterface, ParserInterface
{
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
        // TODO: implement
        return ValidationResult::pass();
    }

    public function isValid(string $input): bool
    {
        return $this->validate($input)->isValid;
    }

    public function parse(string $input): Krs
    {
        // TODO: implement
        throw new ValidationException('Not implemented yet.');
    }

    public function tryParse(string $input): ?Krs
    {
        try {
            return $this->parse($input);
        } catch (ValidationException) {
            return null;
        }
    }
}
