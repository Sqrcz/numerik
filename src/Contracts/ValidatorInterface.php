<?php

declare(strict_types=1);

namespace SlashLab\Numerik\Contracts;

use SlashLab\Numerik\Result\ValidationResult;

interface ValidatorInterface
{
    public function validate(string $input): ValidationResult;

    public function isValid(string $input): bool;

    public function isStrict(): bool;
}
