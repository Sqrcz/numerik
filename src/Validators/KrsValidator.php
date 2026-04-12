<?php

declare(strict_types=1);

namespace SlashLab\Numerik\Validators;

use SlashLab\Numerik\Contracts\ValidatorInterface;
use SlashLab\Numerik\Result\ValidationResult;

final class KrsValidator implements ValidatorInterface
{
    public function validate(string $input): ValidationResult
    {
        // TODO: implement
        return ValidationResult::pass();
    }

    public function isValid(string $input): bool
    {
        return $this->validate($input)->isValid;
    }
}
