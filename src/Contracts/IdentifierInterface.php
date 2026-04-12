<?php

declare(strict_types=1);

namespace SlashLab\Numerik\Contracts;

interface IdentifierInterface
{
    public function getRaw(): string;

    public function getNormalized(): string;

    public function __toString(): string;
}
