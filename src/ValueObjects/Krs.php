<?php

declare(strict_types=1);

namespace SlashLab\Numerik\ValueObjects;

use SlashLab\Numerik\Contracts\IdentifierInterface;

final readonly class Krs implements IdentifierInterface
{
    public function __construct(
        private string $raw,
        private string $normalized,
    ) {
    }

    public function getRaw(): string
    {
        return $this->raw;
    }

    public function getNormalized(): string
    {
        return $this->normalized;
    }

    public function __toString(): string
    {
        return $this->normalized;
    }

    public function getFormatted(): string
    {
        return str_pad($this->normalized, 10, '0', STR_PAD_LEFT);
    }

    public function getNumericValue(): int
    {
        return (int) $this->normalized;
    }
}
