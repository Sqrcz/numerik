<?php

declare(strict_types=1);

namespace SlashLab\Numerik\Contracts;

use SlashLab\Numerik\Exceptions\ValidationException;

interface ParserInterface
{
    /**
     * @throws ValidationException
     */
    public function parse(string $input): IdentifierInterface;

    public function tryParse(string $input): ?IdentifierInterface;
}
