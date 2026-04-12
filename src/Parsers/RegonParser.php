<?php

declare(strict_types=1);

namespace SlashLab\Numerik\Parsers;

use SlashLab\Numerik\Contracts\IdentifierInterface;
use SlashLab\Numerik\Contracts\ParserInterface;
use SlashLab\Numerik\Exceptions\ValidationException;

final class RegonParser implements ParserInterface
{
    public function parse(string $input): IdentifierInterface
    {
        // TODO: implement
        throw new ValidationException('Not implemented yet.');
    }

    public function tryParse(string $input): ?IdentifierInterface
    {
        try {
            return $this->parse($input);
        } catch (ValidationException) {
            return null;
        }
    }
}
