<?php

declare(strict_types=1);

namespace SlashLab\Numerik\ValueObjects;

use SlashLab\Numerik\Contracts\IdentifierInterface;

final readonly class Nrb implements IdentifierInterface
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

    public function getFormatted(): string
    {
        return substr($this->normalized, 0, 2)
            . ' '
            . implode(' ', str_split(substr($this->normalized, 2), 4));
    }

    public function getIban(): string
    {
        return 'PL' . $this->normalized;
    }

    public function getFormattedIban(): string
    {
        return implode(' ', str_split('PL' . $this->normalized, 4));
    }

    public function getCheckDigits(): string
    {
        return substr($this->normalized, 0, 2);
    }

    public function getSortCode(): string
    {
        return substr($this->normalized, 2, 8);
    }

    public function getBankCode(): string
    {
        return substr($this->normalized, 2, 3);
    }

    public function getAccountNumber(): string
    {
        return substr($this->normalized, 10, 16);
    }
}
