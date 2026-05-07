<?php

declare(strict_types=1);

namespace SlashLab\Numerik\ValueObjects;

use SlashLab\Numerik\Contracts\IdentifierInterface;

final readonly class Passport implements IdentifierInterface
{
    public function __construct(
        private string $raw,
        private string $normalized,
    ) {
    }

    #[\Override]
    public function getRaw(): string
    {
        return $this->raw;
    }

    #[\Override]
    public function getNormalized(): string
    {
        return $this->normalized;
    }

    #[\Override]
    public function __toString(): string
    {
        return $this->normalized;
    }

    public function getSeries(): string
    {
        return substr($this->normalized, 0, 2);
    }

    public function getSequentialNumber(): string
    {
        return substr($this->normalized, 2, 6);
    }

    public function getCheckDigit(): string
    {
        return substr($this->normalized, 8, 1);
    }
}
